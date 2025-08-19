<?php
require_once 'includes/db-connect.php';

echo "<h2>Тест подключения к PostgreSQL</h2>";

try {
    $db = getDBConnection();
    echo "✅ Успешное подключение к PostgreSQL<br>";
    
    // Проверка версии PostgreSQL
    $stmt = $db->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "Версия PostgreSQL: " . $version . "<br><br>";
    
    // Проверка существования таблиц
    $stmt = $db->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public'
        ORDER BY table_name
    ");
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables)) {
        echo "✅ Таблицы в базе данных:<br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>{$table}</li>";
            
            // Проверяем количество записей
            $countStmt = $db->query("SELECT COUNT(*) FROM {$table}");
            $count = $countStmt->fetchColumn();
            echo " - записей: {$count}<br>";
        }
        echo "</ul>";
    } else {
        echo "❌ Таблицы не найдены<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Ошибка подключения: " . $e->getMessage() . "<br>";
    echo "Проверьте настройки в includes/db-connect.php";
}

// Проверка ContentManager
echo "<h2>Тест ContentManager</h2>";

try {
    require_once 'includes/functions.php';
    $contentManager = new ContentManager(getDBConnection());
    
    // Проверка получения услуг
    $services = $contentManager->getAllServices();
    echo "✅ Услуги получены: " . count($services) . " шт.<br>";
    
    if (count($services) > 0) {
        echo "Пример услуги: " . $services[0]['service_name'] . " - " . $services[0]['base_price'] . " руб.<br>";
    }
    
    // Проверка получения контента страниц
    $pages = ['home', 'services', 'about', 'reviews'];
    foreach ($pages as $page) {
        $content = $contentManager->getPageContent($page);
        echo "Контент страницы '{$page}': " . count($content) . " блоков<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка ContentManager: " . $e->getMessage();
}
?>