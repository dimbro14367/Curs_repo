<?php
// save_recipe.php - сохранение рецепта в БД с загрузкой файлов

require_once "auth.php";
require_once "db.php";

// Проверяем, авторизован ли пользователь
requireLogin();

// Проверяем, что форма отправлена методом POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: add_recipe.php');
    exit;
}

// Получаем данные из формы
$id_category = $_POST['id_category'] ?? 0;
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$portions = intval($_POST['portions'] ?? 0);
$time_to_cook = intval($_POST['time_to_cook'] ?? 0);
$calories = intval($_POST['calories'] ?? 0);
$heat = intval($_POST['heat'] ?? 1);
$id_author = $_SESSION['user_id'];  // ID автора из сессии

// Подключаемся к БД
$conn = getDB();

// Начинаем транзакцию (все операции выполнятся вместе или не выполнятся вообще)
sqlsrv_begin_transaction($conn);

try {
    // 1. Сохраняем рецепт
    $sql = "INSERT INTO CURS_recipes (id_category, name, description, portions, time_to_cook, calories, heat, id_author, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";
    $stmt = sqlsrv_query($conn, $sql, array($id_category, $name, $description, $portions, $time_to_cook, $calories, $heat, $id_author));
    
    if (!$stmt) throw new Exception('Ошибка сохранения рецепта');
    
    // 2. Получаем ID нового рецепта
    $sql = "SELECT @@IDENTITY as id";
    $stmt = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($stmt);
    $recipe_id = $row['id'];
    
    // 3. Создаем папку для фото
    $foldername = 'recipe_' . $recipe_id . '_' . date('Ymd_His');
    $folder_path = 'images/' . $foldername;
    mkdir($folder_path, 0777, true);
    
    // 4. Сохраняем главное фото
    $ext = pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['main_image']['tmp_name'], $folder_path . '/main.' . $ext);
    
    // 5. Обновляем запись - добавляем имя папки
    $sql = "UPDATE CURS_recipes SET foldername = ? WHERE id = ?";
    sqlsrv_query($conn, $sql, array($foldername, $recipe_id));
    
    // 6. Сохраняем ингредиенты
    if (isset($_POST['ingredients'])) {
        foreach ($_POST['ingredients'] as $line) {
            $line = trim($line);
            if ($line) {
                $sql = "INSERT INTO CURS_ingredients (id_recipe, line) VALUES (?, ?)";
                sqlsrv_query($conn, $sql, array($recipe_id, $line));
            }
        }
    }
    
    // 7. Сохраняем шаги
    if (isset($_POST['steps_text'])) {
        $step_num = 1;
        foreach ($_POST['steps_text'] as $step_text) {
            $step_text = trim($step_text);
            if ($step_text) {
                $photo_name = null;
                // Сохраняем фото шага, если оно есть
                if (isset($_FILES['steps_photo']['tmp_name'][$step_num-1]) && 
                    $_FILES['steps_photo']['error'][$step_num-1] == 0) {
                    $step_ext = pathinfo($_FILES['steps_photo']['name'][$step_num-1], PATHINFO_EXTENSION);
                    $photo_name = 'step_' . $step_num . '.' . $step_ext;
                    move_uploaded_file($_FILES['steps_photo']['tmp_name'][$step_num-1], $folder_path . '/' . $photo_name);
                }
                
                $sql = "INSERT INTO CURS_steps (id_recipe, step_number, step_text, photo_name) VALUES (?, ?, ?, ?)";
                sqlsrv_query($conn, $sql, array($recipe_id, $step_num, $step_text, $photo_name));
                $step_num++;
            }
        }
    }
    
    // Фиксируем транзакцию (все изменения сохраняются)
    sqlsrv_commit($conn);
    sqlsrv_close($conn);
    
    $_SESSION['success'] = 'Рецепт добавлен!';
    header('Location: add_recipe.php');
    
} catch (Exception $e) {
    // Откат транзакции (отменяем все изменения при ошибке)
    sqlsrv_rollback($conn);
    sqlsrv_close($conn);
    $_SESSION['error'] = 'Ошибка: ' . $e->getMessage();
    header('Location: add_recipe.php');
}
?>