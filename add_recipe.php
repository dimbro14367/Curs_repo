<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$conn = getDB();
$categories = sqlsrv_query($conn, "SELECT * FROM CURS_category ORDER BY name");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить рецепт | Кулинарный портал</title>
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
        .form-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .form-container h1 {
            margin-bottom: 20px;
            color: #4a3b2f;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #5b4a38;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e8d9cc;
            border-radius: 10px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .ingredient-item, .step-item {
            background: #fef7f0;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid #e8d9cc;
        }
        .step-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .btn-add {
            background: #4ECDC4;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 10px;
        }
        .btn-remove {
            background: #FF6B4A;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 15px;
            cursor: pointer;
        }
        .btn-submit {
            background: #FF6B4A;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .preview-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-top: 10px;
            border-radius: 8px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
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

    <div class="form-container">
        <h1>➕ Добавить новый рецепт</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form action="save_recipe.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Категория *</label>
                <select name="id_category" required>
                    <option value="">Выберите категорию</option>
                    <?php while($cat = sqlsrv_fetch_array($categories, SQLSRV_FETCH_ASSOC)): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Название блюда *</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Описание (до 140 символов) *</label>
                <textarea name="description" maxlength="140" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Главное фото *</label>
                <input type="file" name="main_image" accept="image/*" required id="mainImage">
                <div id="mainPreview"></div>
            </div>
            
            <div class="form-group">
                <label>Количество порций *</label>
                <input type="number" name="portions" min="1" max="20" required>
            </div>
            
            <div class="form-group">
                <label>Время приготовления (минуты) *</label>
                <input type="number" name="time_to_cook" min="1" required>
            </div>
            
            <div class="form-group">
                <label>Калории (на порцию) *</label>
                <input type="number" name="calories" min="0" required>
            </div>
            
            <div class="form-group">
                <label>Уровень остроты *</label>
                <select name="heat" required>
                    <option value="1">🌶️ 1 - Не острый</option>
                    <option value="2">🌶️🌶️ 2 - Слабый</option>
                    <option value="3">🌶️🌶️🌶️ 3 - Средний</option>
                    <option value="4">🌶️🌶️🌶️🌶️ 4 - Острый</option>
                    <option value="5">🌶️🌶️🌶️🌶️🌶️ 5 - Очень острый</option>
                </select>
            </div>
            
            <h3>🛒 Ингредиенты</h3>
            <div id="ingredients">
                <div class="ingredient-item">
                    <input type="text" name="ingredients[]" placeholder="Например: 500 г спагетти" required>
                    <button type="button" class="btn-remove" onclick="removeIngredient(this)">Удалить</button>
                </div>
            </div>
            <button type="button" class="btn-add" onclick="addIngredient()">+ Добавить ингредиент</button>
            
            <h3>📝 Шаги приготовления</h3>
            <div id="steps">
                <div class="step-item">
                    <div class="step-header">
                        <strong>Шаг 1</strong>
                        <button type="button" class="btn-remove" onclick="removeStep(this)">Удалить</button>
                    </div>
                    <textarea name="steps_text[]" placeholder="Описание шага..." rows="2" required></textarea>
                    <input type="file" name="steps_photo[]" accept="image/*">
                    <div class="step-preview"></div>
                </div>
            </div>
            <button type="button" class="btn-add" onclick="addStep()">+ Добавить шаг</button>
            
            <button type="submit" class="btn-submit">Опубликовать рецепт</button>
        </form>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>О проекте</h3>
                <ul class="footer-links">
                    <li><a href="about.html">О нас</a></li>
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
        // Предпросмотр главного фото при выборе файла
        document.getElementById('mainImage').onchange = function(e) {
    
            // Находим блок для превью и очищаем его
            let preview = document.getElementById('mainPreview');
            preview.innerHTML = '';
    
            // Получаем выбранный файл
            let file = e.target.files[0];
    
            if (file) {
                // Создаем читалку файлов
                let reader = new FileReader();
        
                // Когда файл прочитан - создаем изображение
                reader.onload = function(e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;          // Данные фото в base64
                    img.classList.add('preview-img');   // Стили для превью
                    preview.appendChild(img);           // Вставляем в блок
                }
        
                // Запускаем чтение файла
                reader.readAsDataURL(file);
            }
        }
        
        // Добавление нового поля для ингредиента
        function addIngredient() {
            let div = document.createElement('div');
            div.className = 'ingredient-item';
            div.innerHTML = `
                <input type="text" name="ingredients[]" placeholder="Например: 500 г спагетти" required>
                <button type="button" class="btn-remove" onclick="removeIngredient(this)">Удалить</button>
            `;
            document.getElementById('ingredients').appendChild(div);
        }

        // Удаление ингредиента
        function removeIngredient(btn) {
            btn.parentElement.remove();  // Удаляем родительский блок
        }

        // Добавление нового шага
        function addStep() {
            // Текущее количество шагов для нумерации
            let steps = document.querySelectorAll('.step-item').length;
            
            let div = document.createElement('div');
            div.className = 'step-item';
            div.innerHTML = `
                <div class="step-header">
                    <strong>Шаг ${steps + 1}</strong>
                    <button type="button" class="btn-remove" onclick="removeStep(this)">Удалить</button>
                </div>
                <textarea name="steps_text[]" placeholder="Описание шага..." rows="2" required></textarea>
                <input type="file" name="steps_photo[]" accept="image/*">
                <div class="step-preview"></div>
            `;
            document.getElementById('steps').appendChild(div);
            
            // Настройка превью фото для нового шага
            let fileInput = div.querySelector('input[type="file"]');
            let previewDiv = div.querySelector('.step-preview');
            
            fileInput.onchange = function(e) {
                previewDiv.innerHTML = '';
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-img');
                        previewDiv.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            }
        }

        // Удаление шага с перенумерацией
        function removeStep(btn) {
            let step = btn.closest('.step-item');
            step.remove();  // Удаляем шаг
            
            // Перенумеровываем оставшиеся шаги
            let steps = document.querySelectorAll('.step-item');
            steps.forEach((step, i) => {
                step.querySelector('strong').innerText = `Шаг ${i + 1}`;
            });
        }
        
        document.querySelectorAll('.step-item').forEach(step => {
            let fileInput = step.querySelector('input[type="file"]');
            let previewDiv = step.querySelector('.step-preview');
            if(fileInput && previewDiv) {
                fileInput.onchange = function(e) {
                    previewDiv.innerHTML = '';
                    let file = e.target.files[0];
                    if(file) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            let img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('preview-img');
                            previewDiv.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
        
        document.getElementById('searchInput').addEventListener('keyup', function() {
            window.location.href = 'recipes.php?search=' + encodeURIComponent(this.value);
        });
    </script>
</body>
</html>

<?php sqlsrv_close($conn); ?>