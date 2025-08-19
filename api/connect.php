<?php
require_once '../includes/functions.php';

$db = getDBConnection();
$manager = new ContentManager($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_page':
        $content = $manager->getPageContent($_GET['page']);
        echo json_encode($content);
        break;
        
    case 'update_content':
        // Проверка прав администратора
        if (isAdmin()) {
            $manager->updateContentBlock($_POST['block_id'], $_POST['content']);
            echo json_encode(['status' => 'success']);
        }
        break;
}
?>
