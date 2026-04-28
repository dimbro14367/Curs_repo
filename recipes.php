<?php
require_once "auth.php";
require_once "db.php";
$conn = getDB();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="description" content="Большой каталог рецептов: салаты, супы, паста, десерты и многое другое. Все рецепты с пошаговыми фотографиями и подробными инструкциями.">
    <meta name="keywords" content="все рецепты, рецепты с фото, пошаговые рецепты, каталог рецептов">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рецепты | Кулинарный портал</title>
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
        .main-content {
            flex: 1;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        .cat-btn {
            padding: 10px 25px;
            background: white;
            border: 2px solid #e8d9cc;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
        }
        .cat-btn:hover, .cat-btn.active {
            background: #FF6B4A;
            color: white;
            border-color: #FF6B4A;
        }
        .recipes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .recipes-header h2 {
            font-size: 28px;
            color: #4a3b2f;
        }
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        /* Карточка рецепта - с фиксацией нижней части */
        .recipe-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-img {
            height: 220px;
            background-size: cover;
            background-position: center;
            background-color: #ffb347;
        }
        .recipe-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .recipe-content h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #4a3b2f;
        }
        .recipe-content p {
            color: #7f6e5d;
            margin-bottom: 15px;
            font-size: 14px;
            flex: 1;
        }
        .recipe-meta {
            display: flex;
            gap: 15px;
            margin: 15px 0;
            padding: 10px 0;
            border-top: 1px solid #f0e0d0;
            border-bottom: 1px solid #f0e0d0;
            font-size: 14px;
            color: #6b4e3a;
        }
        .recipe-btn {
            display: inline-block;
            padding: 8px 20px;
            background: none;
            border: 2px solid #FF6B4A;
            color: #FF6B4A;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
            text-align: center;
            margin-top: auto;
        }
        .recipe-btn:hover {
            background: #FF6B4A;
            color: white;
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
            .footer-container { grid-template-columns: 1fr; }
            .recipe-grid { grid-template-columns: 1fr; }
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
                <button class="slider-btn" onclick="prevRecipe()">‹</button>
                <div class="search-box">
                    <div class="search-icon">🔍</div>
                    <input type="text" class="search-input" id="searchInput" placeholder="Поиск рецептов...">
                </div>
                <button class="slider-btn" onclick="nextRecipe()">›</button>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="categories" id="categories">
            <button class="cat-btn active" data-cat="all">🍕 Все рецепты</button>
            <?php
            $cats = sqlsrv_query($conn, "SELECT * FROM CURS_category ORDER BY name");
            while($cat = sqlsrv_fetch_array($cats, SQLSRV_FETCH_ASSOC)) {
                echo '<button class="cat-btn" data-cat="' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</button>';
            }
            ?>
        </div>

        <div class="recipes-header">
            <h2>Все рецепты</h2>
            <?php
            $countSql = "SELECT COUNT(*) as total FROM CURS_recipes";
            $countStmt = sqlsrv_query($conn, $countSql);
            $countRow = sqlsrv_fetch_array($countStmt);
            $totalRecipes = $countRow['total'];
            ?>
            <span>найдено <?= $totalRecipes ?> рецептов</span>
        </div>

        <div class="recipe-grid" id="recipeGrid">
            <?php
            $sql = "SELECT r.*, c.name as cat_name 
                    FROM CURS_recipes r 
                    LEFT JOIN CURS_category c ON r.id_category = c.id 
                    ORDER BY r.created_at DESC";
            $recipes = sqlsrv_query($conn, $sql);
            
            while($row = sqlsrv_fetch_array($recipes, SQLSRV_FETCH_ASSOC)) {
                $img_path = 'images/placeholder.jpg';
                if (!empty($row['foldername'])) {
                    $folder = 'images/' . $row['foldername'];
                    if (is_dir($folder)) {
                        $files = glob($folder . '/main.*');
                        if (!empty($files) && file_exists($files[0])) {
                            $img_path = $files[0];
                        }
                    }
                }
                $heat = str_repeat('🌶️', $row['heat']);
                if ($heat == '') $heat = '🌶️';
            ?>
            <div class="recipe-card" data-cat="<?= $row['id_category'] ?>">
                <div class="recipe-img" style="background-image: url('<?= $img_path ?>')"></div>
                <div class="recipe-content">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <div class="recipe-meta">
                        <span>⏱️ <?= $row['time_to_cook'] ?> мин</span>
                        <span>🔥 <?= $heat ?></span>
                        <span>👥 <?= $row['portions'] ?> порц</span>
                    </div>
                    <a href="recipe.php?id=<?= $row['id'] ?>" class="recipe-btn">Смотреть рецепт →</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>

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
        const btns = document.querySelectorAll('.cat-btn');
        const cards = document.querySelectorAll('.recipe-card');

        btns.forEach(btn => {
            btn.onclick = function() {
                btns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const cat = this.dataset.cat;
                let visible = 0;
                cards.forEach(card => {
                    if (cat === 'all' || card.dataset.cat === cat) {
                        card.style.display = 'block';
                        visible++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                document.querySelector('.recipes-header span').innerText = `найдено ${visible} рецептов`;
            }
        });
        
        // Поиск рецептов по названию и описанию
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let visible = 0;
                cards.forEach(card => {
                    let title = card.querySelector('h3').innerText.toLowerCase();
                    let desc = card.querySelector('p').innerText.toLowerCase();
                    if (title.includes(filter) || desc.includes(filter)) {
                        card.style.display = 'block';
                        visible++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                document.querySelector('.recipes-header span').innerText = `найдено ${visible} рецептов`;
            });
        }
        
        let currentIndex = 0;
        function prevRecipe() {
            let visibleCards = Array.from(cards).filter(c => c.style.display !== 'none');
            if(visibleCards.length) {
                currentIndex = (currentIndex - 1 + visibleCards.length) % visibleCards.length;
                visibleCards[currentIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        function nextRecipe() {
            let visibleCards = Array.from(cards).filter(c => c.style.display !== 'none');
            if(visibleCards.length) {
                currentIndex = (currentIndex + 1) % visibleCards.length;
                visibleCards[currentIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    </script>
</body>
</html>

<?php sqlsrv_close($conn); ?>