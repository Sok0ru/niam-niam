<?php
header('Content-Type: application/json');

$db = getDBConnection();
$tracker = new ActivityTracker($db);

$data = json_decode(file_get_contents('php://input'), true);

try {
    switch ($_GET['action']) {
        case 'session':
            $sessionId = $tracker->startSession(
                $data['visitorId'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT'],
                $data['referrer'],
                $data['landingPage']
            );
            echo json_encode(['sessionId' => $sessionId]);
            break;
            
        case 'pageview':
            $pageId = getPageIdByUrl($data['pageUrl']);
            $tracker->trackPageView(
                $data['sessionId'],
                $pageId,
                0, // Время будет обновлено при уходе
                0  // Глубина прокрутки будет обновлена при уходе
            );
            echo json_encode(['status' => 'success']);
            break;
            
        case 'pageleave':
            $pageId = getPageIdByUrl($data['pageUrl']);
            $db->query("
                UPDATE page_views 
                SET time_spent = {$data['timeSpent']}, scroll_depth = {$data['scrollDepth']}
                WHERE session_id = '{$data['sessionId']}' AND page_id = $pageId
                ORDER BY view_time DESC LIMIT 1
            ");
            echo json_encode(['status' => 'success']);
            break;
            
        case 'action':
            $tracker->trackAction(
                $data['sessionId'],
                $data['actionType'],
                $data['elementId'],
                $data['elementText'],
                $data['actionData'] ?? null
            );
            echo json_encode(['status' => 'success']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function getPageIdByUrl($url) {
    global $db;
    $path = parse_url($url, PHP_URL_PATH);
    $pageName = str_replace('/', '', $path) ?: 'home';
    
    $result = $db->query("SELECT page_id FROM content_pages WHERE page_name = '$pageName'");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['page_id'];
    }
    return 1; // По умолчанию главная страница
}
?>