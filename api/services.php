<?php
header('Content-Type: application/json');
require_once '../includes/db-connect.php';
require_once '../includes/functions.php';

$db = getDBConnection();
$manager = new ContentManager($db);

$action = $_GET['action'] ?? '';
$response = [];

try {
    switch ($action) {
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $manager->updateService(
                    $_POST['service_id'],
                    [
                        'service_name' => $_POST['name'],
                        'base_price' => (float)$_POST['price']
                    ]
                );
                $response = ['status' => 'success'];
            }
            break;
            
        case 'list':
            $response = $manager->getAllServices();
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    $response = ['error' => $e->getMessage()];
}

echo json_encode($response);
?>