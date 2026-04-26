<?php
// Запуск сессии для хранения данных о пользователе
session_start();

// Функция проверки, авторизован ли пользователь
function isLoggedIn() {
    // Если в сессии есть user_id - значит пользователь вошел
    return isset($_SESSION['user_id']);
}

// Функция требует авторизации (если не авторизован - редирект на вход)
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');  // Перенаправляем на страницу входа
        exit;                           
    }
}

// Функция получения текущего пользователя
function getCurrentUser() {
    // Возвращаем логин пользователя из сессии или null
    return $_SESSION['user_login'] ?? null;
}
?>