<?php
require_once 'includes/db-connect.php';
require_once 'includes/functions.php';

echo "<h1>Полная проверка системы с PostgreSQL</h1>";

// 1. Проверка базового подключения
echo "<h2>1. Базовое подключение</h2>";
try {
    $db = getDBConnection();
    echo "✅ Подключение к PostgreSQL успешно<br>";
    
    // Проверяем доступность базы
    $stmt = $db->query("SELECT current_database()");
    $dbName = $stmt->fetchColumn();
    echo "База данных: {$dbName}<br>";
    
} catch (PDOException $e) {
    die("❌ Ошибка подключения: " . $e->getMessage());
}

// 2. Проверка ContentManager
echo "<h2>2. ContentManager</h2>";
try {
    $contentManager = new ContentManager($db);
    
    // Проверка услуг
    $services = $contentManager->getAllServices();
    echo "✅ Услуги: " . count($services) . " шт.<br>";
    
    // Проверка контента страниц
    $pagesToCheck = ['home', 'services', 'about', 'reviews'];
    foreach ($pagesToCheck as $page) {
        $content = $contentManager->getPageContent($page);
        echo "Страница '{$page}': " . count($content) . " блоков<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка ContentManager: " . $e->getMessage() . "<br>";
}

// 3. Проверка структуры базы
echo "<h2>3. Структура базы данных</h2>";
$requiredTables = ['content_pages', 'services', 'content_blocks', 'content_sections'];

foreach ($requiredTables as $table) {
    try {
        $stmt = $db->query("SELECT COUNT(*) FROM {$table}");
        $count = $stmt->fetchColumn();
        echo "✅ {$table}: {$count} записей<br>";
    } catch (PDOException $e) {
        echo "❌ {$table}: таблица не найдена<br>";
    }
}

// 4. Проверка данных
echo "<h2>4. Проверка данных</h2>";
$checks = [
    'content_pages' => "SELECT COUNT(*) FROM content_pages WHERE page_name IN ('home', 'services', 'about', 'reviews')",
    'services' => "SELECT COUNT(*) FROM services WHERE base_price > 0",
    'content_blocks' => "SELECT COUNT(*) FROM content_blocks WHERE is_active = true"
];

foreach ($checks as $checkName => $sql) {
    try {
        $stmt = $db->query($sql);
        $count = $stmt->fetchColumn();
        echo "✅ {$checkName}: {$count} записей<br>";
    } catch (PDOException $e) {
        echo "❌ {$checkName}: ошибка проверки<br>";
    }
}

// 5. Тест производительности
echo "<h2>5. Производительность</h2>";
$start = microtime(true);

// Выполняем несколько запросов
for ($i = 0; $i < 10; $i++) {
    $db->query("SELECT 1");
}

$time = round((microtime(true) - $start) * 1000, 2);
echo "Время 10 простых запросов: {$time} ms<br>";

echo "<h2>🎉 Проверка завершена!</h2>";
echo "Если все тесты пройдены, система готова к работе с PostgreSQL.";
?>