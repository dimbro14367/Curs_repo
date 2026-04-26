<?php
function getDB() {
    // данные бд
    $server_name = "stud-mssql.sttec.yar.ru,38325";
    $conInfo = array(
        "Database" => "user30_db",
        "UID" => "user30_db",
        "PWD" => "user30",
        "CharacterSet" => "UTF-8"
    );
    // подключение
    $conn = sqlsrv_connect($server_name, $conInfo);
    
    if (!$conn) {
        die("Ошибка подключения: " . print_r(sqlsrv_errors(), true));
    }
    
    return $conn;
}
?>