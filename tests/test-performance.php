<?php
require_once 'includes/db-connect.php';
require_once 'includes/functions.php';

echo "<h2>Тест производительности PostgreSQL</h2>";

$db = getDBConnection();
$contentManager = new ContentManager($db);

// Тест скорости запросов
$tests = [
    'getAllServices' => function() use ($contentManager) {
        return $contentManager->getAllServices();
    },
    'getPageContent' => function() use ($contentManager) {
        return $contentManager->getPageContent('home');
    }
];

foreach ($tests as $testName => $testFunction) {
    $start = microtime(true);
    
    try {
        $result = $testFunction();
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        
        echo "{$testName}: ✅ {$time} ms";
        if (is_array($result)) {
            echo " (" . count($result) . " записей)";
        }
        echo "<br>";
        
    } catch (Exception $e) {
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        echo "{$testName}: ❌ {$time} ms - Ошибка: " . $e->getMessage() . "<br>";
    }
}

// Тест подключения
echo "<h3>Тест подключения</h3>";
$connectionTests = 5;
$totalTime = 0;

for ($i = 0; $i < $connectionTests; $i++) {
    $start = microtime(true);
    
    try {
        $testDb = getDBConnection();
        $testDb->query("SELECT 1");
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        $totalTime += $time;
        
        echo "Подключение {$i}: ✅ {$time} ms<br>";
        
    } catch (Exception $e) {
        echo "Подключение {$i}: ❌ Ошибка<br>";
    }
}

echo "Среднее время подключения: " . round($totalTime / $connectionTests, 2) . " ms<br>";
?>