<?php
require_once 'db-connect.php';

header('Content-Type: application/json');

$db = getDBConnection();
$data = json_decode(file_get_contents('php://input'), true);

class ActivityTracker {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function startSession($visitorId, $ip, $userAgent, $referrer, $landingPage) {
        // Определяем тип устройства
        $deviceType = $this->detectDeviceType($userAgent);
        
        // Создаем или обновляем посетителя
        $stmt = $this->conn->prepare("
            INSERT INTO visitors (visitor_id, ip_address, user_agent, device_type)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                last_visit = CURRENT_TIMESTAMP,
                ip_address = VALUES(ip_address),
                user_agent = VALUES(user_agent),
                device_type = VALUES(device_type)
        ");
        $stmt->bind_param("ssss", $visitorId, $ip, $userAgent, $deviceType);
        $stmt->execute();
        
        // Создаем новую сессию
        $sessionId = bin2hex(random_bytes(32));
        $stmt = $this->conn->prepare("
            INSERT INTO user_sessions 
            (session_id, visitor_id, referrer_url, landing_page)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssss", $sessionId, $visitorId, $referrer, $landingPage);
        $stmt->execute();
        
        return $sessionId;
    }
    
    public function trackPageView($sessionId, $pageId, $timeSpent, $scrollDepth) {
        $stmt = $this->conn->prepare("
            INSERT INTO page_views 
            (session_id, page_id, time_spent, scroll_depth)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("siii", $sessionId, $pageId, $timeSpent, $scrollDepth);
        return $stmt->execute();
    }
    
    public function trackAction($sessionId, $actionType, $elementId, $elementText, $actionData = null) {
        $jsonData = $actionData ? json_encode($actionData) : null;
        
        $stmt = $this->conn->prepare("
            INSERT INTO user_actions 
            (session_id, action_type, element_id, element_text, action_data)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss", $sessionId, $actionType, $elementId, $elementText, $jsonData);
        return $stmt->execute();
    }
    
    private function detectDeviceType($userAgent) {
        if (preg_match('/bot|crawl|slurp|spider/i', $userAgent)) {
            return 'bot';
        }
        
        if (preg_match('/mobile|android|iphone|ipad|ipod/i', $userAgent)) {
            return preg_match('/tablet|ipad/i', $userAgent) ? 'tablet' : 'mobile';
        }
        
        return 'desktop';
    }
}
?>