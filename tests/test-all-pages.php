<?php
// Тестирование всех страниц
$pages = ['index.php', 'about.php', 'services.php', 'reviews.php'];

echo "<h2>Тестирование страниц</h2>";

foreach ($pages as $page) {
    echo "Проверка {$page}: ";
    
    if (file_exists($page)) {
        // Проверяем, нет ли синтаксических ошибок
        $output = shell_exec("php -l {$page} 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ OK (синтаксис верный)<br>";
        } else {
            echo "❌ Синтаксическая ошибка: " . htmlspecialchars($output) . "<br>";
        }
    } else {
        echo "❌ Файл не найден<br>";
    }
}

// Проверка включений
echo "<h2>Проверка включаемых файлов</h2>";

$includes = ['includes/header.php', 'includes/footer.php', 'includes/db-connect.php', 'includes/functions.php'];

foreach ($includes as $include) {
    echo "Проверка {$include}: ";
    
    if (file_exists($include)) {
        $output = shell_exec("php -l {$include} 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ OK<br>";
        } else {
            echo "❌ Ошибка: " . htmlspecialchars($output) . "<br>";
        }
    } else {
        echo "❌ Файл не найден<br>";
    }
}
?>