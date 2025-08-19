<?php
require_once 'includes/db-connect.php';
require_once 'includes/functions.php';

echo "<h2>Проверка данных в PostgreSQL</h2>";

try {
    $db = getDBConnection();
    $contentManager = new ContentManager($db);
    
    // 1. Проверка страниц
    echo "<h3>1. Страницы контента</h3>";
    $stmt = $db->query("SELECT * FROM content_pages");
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($pages) > 0) {
        echo "✅ Страницы найдены:<br>";
        foreach ($pages as $page) {
            echo "- {$page['page_name']}: {$page['page_title']}<br>";
        }
    } else {
        echo "❌ Нет страниц в базе<br>";
    }
    
    // 2. Проверка услуг
    echo "<h3>2. Услуги</h3>";
    $services = $contentManager->getAllServices();
    
    if (count($services) > 0) {
        echo "✅ Услуги найдены:<br>";
        foreach ($services as $service) {
            echo "- {$service['service_name']}: {$service['base_price']} руб.<br>";
            echo "  Описание: {$service['short_description']}<br><br>";
        }
    } else {
        echo "❌ Нет услуг в базе<br>";
    }
    
    // 3. Проверка контентных блоков
    echo "<h3>3. Контентные блоки</h3>";
    $stmt = $db->query("
        SELECT cp.page_name, cb.block_type, cb.content 
        FROM content_blocks cb
        JOIN content_sections cs ON cb.section_id = cs.section_id
        JOIN content_pages cp ON cs.page_id = cp.page_id
        LIMIT 5
    ");
    $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($blocks) > 0) {
        echo "✅ Контентные блоки найдены:<br>";
        foreach ($blocks as $block) {
            echo "- {$block['page_name']} [{$block['block_type']}]: " 
                 . htmlspecialchars(substr($block['content'], 0, 50)) . "...<br>";
        }
    } else {
        echo "❌ Нет контентных блоков в базе<br>";
    }
    
    // 4. Проверка работы методов ContentManager
    echo "<h3>4. Тестирование ContentManager</h3>";
    
    // Получение контента главной страницы
    $homeContent = $contentManager->getPageContent('home');
    echo "Контент главной страницы: " . count($homeContent) . " блоков<br>";
    
    // Тест обновления (если есть данные)
    if (count($blocks) > 0) {
        $firstBlock = $blocks[0];
        echo "Первый блок: ID " . ($firstBlock['block_id'] ?? 'N/A') . "<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Ошибка базы данных: " . $e->getMessage();
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage();
}
?>
