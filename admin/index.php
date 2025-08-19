<?php
require_once '../includes/functions.php';
require_once 'auth.php'; // Проверка авторизации

$db = getDBConnection();
$manager = new ContentManager($db);

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: /admin/login.php');
    exit;
}

$db = getDBConnection();
$contentManager = new ContentManager($db);

// Обработка POST-запросов на обновление
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_content'])) {
        $contentManager->updateContentBlock($_POST['block_id'], $_POST['content']);
        $message = "Контент успешно обновлен";
    }
    elseif (isset($_POST['update_service'])) {
        $contentManager->updateServicePrice(
            $_POST['service_id'],
            (float)$_POST['price']
        );
        $message = "Цена услуги обновлена";
    }
}

// Получение данных для отображения
$services = $contentManager->getAllServices();
$mainPageContent = $contentManager->getPageContent('home');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <style>
        .content-block { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .service-item { display: flex; justify-content: space-between; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Управление контентом</h1>
    
    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <section>
        <h2>Главная страница</h2>
        <?php foreach ($mainPageContent as $block): ?>
            <div class="content-block">
                <form method="POST">
                    <input type="hidden" name="block_id" value="<?= $block['block_id'] ?>">
                    <h3><?= htmlspecialchars($block['block_type']) ?></h3>
                    <textarea name="content" style="width: 100%; height: 100px;">
                        <?= htmlspecialchars($block['content']) ?>
                    </textarea>
                    <button type="submit" name="update_content">Сохранить</button>
                </form>
            </div>
        <?php endforeach; ?>
    </section>
    
    <section>
        <h2>Услуги и цены</h2>
        <?php foreach ($services as $service): ?>
            <div class="service-item">
                <form method="POST">
                    <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">
                    <h3><?= htmlspecialchars($service['service_name']) ?></h3>
                    <p><?= htmlspecialchars($service['short_description']) ?></p>
                    <div>
                        <input type="number" name="price" 
                               value="<?= $service['base_price'] ?>" step="1000">
                        <button type="submit" name="update_service">Обновить цену</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </section>
</body>
</html>
?>