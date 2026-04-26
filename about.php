<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О нас | Кулинарный портал</title>
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
        .search-icon {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #FF8E53;
            font-size: 20px;
        }
        .content {
            flex: 1;
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .content h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #4a3b2f;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #7f6e5d;
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
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        @media (max-width: 768px) {
            .nav-container { flex-direction: column; gap: 15px; }
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
                    <?php 
                    if(isset($_SESSION['user_id'])): ?>
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

    <div class="content">
        <h1>О нас</h1>
        <p>Кулинарный портал — это место, где собираются любители вкусной еды. Мы создали этот сайт, чтобы делиться проверенными рецептами, кулинарными секретами и вдохновлять на кулинарные подвиги.</p>
        <p>Наша команда — это профессиональные повара, food-блогеры и просто люди, которые любят готовить. Каждый рецепт проходит проверку, чтобы вы могли быть уверены в результате.</p>
        <p>Присоединяйтесь к нашему кулинарному сообществу, делитесь своими рецептами и открывайте новые вкусы вместе с нами!</p>
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