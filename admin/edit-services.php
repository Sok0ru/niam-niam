<?php
require_once '../includes/functions.php';
require_once 'auth.php';

$db = getDBConnection();
$contentManager = new ContentManager($db);
$services = $contentManager->getAllServices();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Управление услугами</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Управление услугами</h1>
        
        <div class="services-list">
            <?php foreach ($services as $service): ?>
            <div class="service-item">
                <form method="POST" action="/api/services.php?action=update">
                    <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">
                    
                    <div class="form-group">
                        <label>Название услуги:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($service['service_name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Цена:</label>
                        <input type="number" name="price" value="<?= $service['base_price'] ?>" step="1000" required>
                    </div>
                    
                    <button type="submit" class="save-btn">Сохранить</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
