<?php
require_once "auth.php";
require_once "db.php";

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: recipes.php');
    exit;
}

$conn = getDB();

$sql = "SELECT r.*, c.name as cat_name FROM CURS_recipes r 
        LEFT JOIN CURS_category c ON r.id_category = c.id 
        WHERE r.id = ?";
$stmt = sqlsrv_query($conn, $sql, array($id));
$recipe = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$recipe) {
    header('Location: recipes.php');
    exit;
}

$ing_stmt = sqlsrv_query($conn, "SELECT * FROM CURS_ingredients WHERE id_recipe = ?", array($id));
$steps_stmt = sqlsrv_query($conn, "SELECT * FROM CURS_steps WHERE id_recipe = ? ORDER BY step_number", array($id));

$main_img = 'images/placeholder.jpg';
if (!empty($recipe['foldername'])) {
    $folder = 'images/' . $recipe['foldername'];
    if (is_dir($folder)) {
        $files = glob($folder . '/main.*');
        if (!empty($files) && file_exists($files[0])) {
            $main_img = $files[0];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="description" content="Пошаговый рецепт приготовления <?= htmlspecialchars($recipe['name']) ?>. Ингредиенты, время приготовления, калорийность и полезные советы.">
    <meta name="keywords" content="рецепт <?= htmlspecialchars($recipe['name']) ?>, как приготовить <?= htmlspecialchars($recipe['name']) ?>, пошаговый рецепт">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['name']) ?> | Кулинарный портал</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Poppins', system-ui, sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #fff9f5;
            color: #4a3b2f;
        }
        .header {
            background: linear-gradient(135deg, #FF6B4A, #FF8E53);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .top-header {
            background-color: rgba(255, 255, 255, 0.08);
            padding: 12px 0;
        }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu-left, .menu-right {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .menu-btn {
            display: inline-block;
            padding: 10px 22px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 15px;
        }
        .menu-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        .add-recipe-btn {
            background-color: #FFB347;
            border-color: #FFB347;
            color: #4a3b2c;
        }
        .login-btn {
            background-color: #4ECDC4;
            border-color: #4ECDC4;
            color: #2a3b3a;
        }
        .search-section {
            padding: 25px 0;
            background: linear-gradient(135deg, #FFF1E6, #FFF9F0);
            border-bottom: 2px solid #FFE4D6;
        }
        .search-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .slider-btn {
            background: #FF6B4A;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            border: none;
        }
        .slider-btn:hover {
            background: #FF8E53;
            transform: scale(1.1);
        }
        .search-box {
            flex-grow: 1;
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 18px 25px 18px 60px;
            border: 2px solid #FFD8C4;
            border-radius: 50px;
            font-size: 16px;
            background: white;
        }
        .search-input:focus {
            outline: none;
            border-color: #FF6B4A;
        }
        .search-icon {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #FF8E53;
            font-size: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .recipe-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .main-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .info {
            padding: 30px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #4a3b2f;
        }
        .category {
            display: inline-block;
            background: #FF6B4A;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .desc {
            color: #7f6e5d;
            line-height: 1.6;
            margin: 20px 0;
        }
        .meta {
            display: flex;
            gap: 30px;
            padding: 20px 0;
            border-top: 1px solid #f0e0d0;
            border-bottom: 1px solid #f0e0d0;
            margin-bottom: 30px;
        }
        .section {
            margin: 30px 0;
        }
        .section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #4a3b2f;
        }
        .ingredients-list {
            list-style: none;
            padding: 0;
        }
        .ingredients-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0e0d0;
            color: #5b4a38;
        }
        .step {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #fef7f0;
            border-radius: 15px;
        }
        .step-num {
            width: 40px;
            height: 40px;
            background: #FF6B4A;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
        }
        .step-text {
            flex: 1;
            color: #5b4a38;
            line-height: 1.5;
        }
        .step-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 25px;
            background: #FF6B4A;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #FF8E53;
            transform: translateY(-2px);
        }
        .footer {
            background: linear-gradient(135deg, #6B4E3A, #8B7356);
            color: white;
            margin-top: auto;
            padding: 50px 0 20px;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
        }
        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            position: relative;
        }
        .footer-section h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background: #FFB347;
        }
        .footer-links {
            list-style: none;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        .footer-links a:hover {
            color: #FFB347;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        @media (max-width: 768px) {
            .nav-container { flex-direction: column; gap: 15px; }
            .step { flex-direction: column; }
            .step-img { width: 100%; height: auto; }
            .footer-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="top-header">
            <nav class="nav-container">
                <div class="menu-left">
                    <a href="main.php" class="menu-btn">🏠 Главная</a>
                    <a href="about.php" class="menu-btn">👥 О нас</a>
                    <a href="recipes.php" class="menu-btn">🍳 Рецепты</a>
                </div>
                <div class="menu-right">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <span style="color: white; padding: 10px;">Привет, <?= $_SESSION['user_login'] ?></span>
                        <a href="logout.php" class="menu-btn login-btn">🚪 Выйти</a>
                    <?php else: ?>
                        <a href="login.php" class="menu-btn login-btn">🚪 Вход</a>
                    <?php endif; ?>
                    <a href="add_recipe.php" class="menu-btn add-recipe-btn">➕ Добавить рецепт</a>
                </div>
            </nav>
        </div>
        <div class="search-section">
            <div class="search-container">
                <button class="slider-btn" onclick="window.location.href='recipes.php'">‹</button>
                <div class="search-box">
                    <div class="search-icon">🔍</div>
                    <input type="text" class="search-input" id="searchInput" placeholder="Поиск рецептов...">
                </div>
                <button class="slider-btn" onclick="window.location.href='recipes.php'">›</button>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="recipe-card">
            <img src="<?= $main_img ?>" class="main-img" alt="<?= htmlspecialchars($recipe['name']) ?>">
            <div class="info">
                <div class="category"><?= htmlspecialchars($recipe['cat_name']) ?></div>
                <h1><?= htmlspecialchars($recipe['name']) ?></h1>
                <div class="meta">
                    <span>👥 <?= $recipe['portions'] ?> порции</span>
                    <span>⏱ <?= $recipe['time_to_cook'] ?> минут</span>
                    <span>🔥 <?= str_repeat('🌶️', $recipe['heat']) ?></span>
                    <span>🍗 <?= $recipe['calories'] ?> ккал</span>
                </div>
                <div class="desc"><?= nl2br(htmlspecialchars($recipe['description'])) ?></div>
                
                <div class="section">
                    <h2>🛒 Ингредиенты</h2>
                    <ul class="ingredients-list">
                        <?php 
                        $has_ingredients = false;
                        while($ing = sqlsrv_fetch_array($ing_stmt, SQLSRV_FETCH_ASSOC)): 
                            $has_ingredients = true;
                        ?>
                            <li>• <?= htmlspecialchars($ing['line']) ?></li>
                        <?php endwhile; ?>
                        <?php if(!$has_ingredients): ?>
                            <li>Ингредиенты не добавлены</li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="section">
                    <h2>📝 Приготовление</h2>
                    <?php 
                    $has_steps = false;
                    while($step = sqlsrv_fetch_array($steps_stmt, SQLSRV_FETCH_ASSOC)): 
                        $has_steps = true;
                        $step_img = '';
                        if (!empty($step['photo_name']) && !empty($recipe['foldername'])) {
                            $step_img_path = 'images/' . $recipe['foldername'] . '/' . $step['photo_name'];
                            if (file_exists($step_img_path)) {
                                $step_img = $step_img_path;
                            }
                        }
                    ?>
                    <div class="step">
                        <div class="step-num"><?= $step['step_number'] ?></div>
                        <div class="step-text"><?= nl2br(htmlspecialchars($step['step_text'])) ?></div>
                        <?php if($step_img): ?>
                            <img src="<?= $step_img ?>" class="step-img">
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                    <?php if(!$has_steps): ?>
                        <p>Шаги приготовления не добавлены</p>
                    <?php endif; ?>
                </div>
                
                <a href="recipes.php" class="back-btn">← Вернуться к рецептам</a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>О проекте</h3>
                <ul class="footer-links">
                    <li><a href="about.php">О нас</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Рецепты</h3>
                <ul class="footer-links">
                    <li><a href="recipes.php">Все рецепты</a></li>
                    <li><a href="add_recipe.php">Добавить рецепт</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Контакты</h3>
                <ul class="footer-links">
                    <li><a href="tel:+78001234567">+7 (800) 123-45-67</a></li>
                    <li><a href="mailto:info@recipe.ru">info@recipe.ru</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Мы в соцсетях</h3>
                <ul class="footer-links">
                    <li><a href="#vk">ВКонтакте</a></li>
                    <li><a href="#telegram">Telegram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Кулинарный портал. Все права защищены.</p>
        </div>
    </footer>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            window.location.href = 'recipes.php?search=' + encodeURIComponent(this.value);
        });
    </script>
</body>
</html>

<?php sqlsrv_close($conn); ?>