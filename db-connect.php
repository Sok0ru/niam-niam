<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'ваш_пользователь');
define('DB_PASS', 'ваш_пароль');
define('DB_NAME', 'tolyatti_remont');
define('DB_PORT', '5432');

function getDBConnection() {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";";
    
    try {
        $conn = new PDO($dsn, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET NAMES 'UTF8'");
        return $conn;
    } catch (PDOException $e) {
        die("Ошибка подключения к PostgreSQL: " . $e->getMessage());
    }
}
?>