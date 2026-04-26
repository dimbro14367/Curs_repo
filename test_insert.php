<?php
require_once "db.php";

$conn = getDB();

// 1. Проверяем подключение
if (!$conn) {
    die("Нет подключения к БД");
}
echo "1. Подключение OK<br>";

// 2. Вставляем тестовый рецепт
$sql = "INSERT INTO CURS_recipes (id_category, name, description, portions, time_to_cook, calories, heat, created_at) 
        VALUES (1, 'Тест', 'Описание', 2, 30, 500, 3, GETDATE())";
$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die("Ошибка вставки рецепта: " . print_r(sqlsrv_errors(), true));
}
echo "2. Рецепт добавлен<br>";

// Получаем ID
$sql = "SELECT SCOPE_IDENTITY() as id";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt);
$recipe_id = $row['id'];
echo "3. ID рецепта: $recipe_id<br>";

// 4. Вставляем ингредиент
$line = "500 г спагетти";
$sql = "INSERT INTO CURS_ingredients (id_recipe, line) VALUES (?, ?)";
$stmt = sqlsrv_query($conn, $sql, array($recipe_id, $line));

if (!$stmt) {
    die("Ошибка вставки ингредиента: " . print_r(sqlsrv_errors(), true));
}
echo "4. Ингредиент добавлен: $line<br>";

// 5. Вставляем шаг
$step_text = "Сварить пасту";
$sql = "INSERT INTO CURS_steps (id_recipe, step_number, step_text, photo_name) VALUES (?, 1, ?, NULL)";
$stmt = sqlsrv_query($conn, $sql, array($recipe_id, $step_text));

if (!$stmt) {
    die("Ошибка вставки шага: " . print_r(sqlsrv_errors(), true));
}
echo "5. Шаг добавлен<br>";

sqlsrv_close($conn);
echo "ВСЕ УСПЕШНО!";
?>