<?php
require_once '../includes/functions.php';

echo "<h2>👨‍💼 Тест админ-панели</h2>";

try {
    $manager = new ContentManager(getDBConnection());
    
    // Проверка обновления контента
    $testContent = "Тестовое обновление " . date('Y-m-d H:i:s');
    $result = $manager->updateContentBlock(1, $testContent);
    
    if ($result) {
        echo "✅ Обновление контента работает<br>";
    } else {
        echo "❌ Ошибка обновления контента<br>";
    }
    
    // Проверка обновления цен
    $result = $manager->updateServicePrice(1, 999999.99);
    if ($result) {
        echo "✅ Обновление цен работает<br>";
        
        // Проверяем новую цену
        $services = $manager->getAllServices();
        foreach ($services as $service) {
            if ($service['service_id'] == 1) {
                echo "Новая цена услуги '{$service['service_name']}': {$service['base_price']} руб.<br>";
            }
        }
    } else {
        echo "❌ Ошибка обновления цен<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка админ-панели: " . $e->getMessage();
}
?>