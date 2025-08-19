<?php
require_once 'includes/db-connect.php';

try {
    $db = getDBConnection();
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Проверка таблиц базы данных</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .table-info { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
            .success { color: green; }
            .error { color: red; }
            .warning { color: orange; }
        </style>
    </head>
    <body>
    <h2>🔍 Проверка таблиц базы данных</h2>";
    
    // Проверка подключения
    echo "<div class='table-info success'>✅ Подключение к PostgreSQL успешно</div>";
    
    // Получаем список таблиц
    $stmt = $db->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public'
        ORDER BY table_name
    ");
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables)) {
        echo "<h3>📊 Найдено таблиц: " . count($tables) . "</h3>";
        
        foreach ($tables as $table) {
            echo "<div class='table-info'>";
            echo "<h4>📋 Таблица: <strong>{$table}</strong></h4>";
            
            try {
                // Количество записей
                $countStmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
                $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "Количество записей: <strong>{$count}</strong><br>";
                
                // Структура таблицы
                $structureStmt = $db->query("
                    SELECT column_name, data_type, is_nullable 
                    FROM information_schema.columns 
                    WHERE table_name = '{$table}'
                    ORDER BY ordinal_position
                ");
                
                echo "Структура: ";
                $columns = [];
                while ($col = $structureStmt->fetch(PDO::FETCH_ASSOC)) {
                    $columns[] = $col['column_name'] . " (" . $col['data_type'] . ")";
                }
                echo implode(', ', $columns) . "<br>";
                
                // Пример данных (если есть записи)
                if ($count > 0) {
                    $sampleStmt = $db->query("SELECT * FROM {$table} LIMIT 3");
                    $samples = $sampleStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo "Пример данных:<br>";
                    echo "<pre>" . htmlspecialchars(json_encode($samples, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
                }
                
            } catch (PDOException $e) {
                echo "<span class='error'>❌ Ошибка чтения таблицы: " . $e->getMessage() . "</span><br>";
            }
            
            echo "</div>";
        }
        
    } else {
        echo "<div class='table-info warning'>⚠️ Таблицы не найдены. Выполните init_db.sql</div>";
    }
    
    // Проверка необходимых таблиц
    echo "<h3>✅ Проверка обязательных таблиц:</h3>";
    $requiredTables = ['content_pages', 'services', 'visitors', 'user_sessions'];
    
    foreach ($requiredTables as $reqTable) {
        if (in_array($reqTable, $tables)) {
            echo "<div class='success'>✅ {$reqTable} - существует</div>";
        } else {
            echo "<div class='error'>❌ {$reqTable} - отсутствует</div>";
        }
    }
    
    echo "</body></html>";
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Ошибка подключения к базе данных: " . $e->getMessage() . "</div>";
    echo "<div>Проверьте настройки в includes/db-connect.php</div>";
}
?>