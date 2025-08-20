<?php
require_once 'db-connect.php';

class ContentManager {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getPageContent($pageName) {
        $stmt = $this->conn->prepare("
            SELECT cb.block_type, cb.content, cb.meta_data 
            FROM content_blocks cb
            JOIN content_sections cs ON cb.section_id = cs.section_id
            JOIN content_pages cp ON cs.page_id = cp.page_id
            WHERE cp.page_name = :pageName AND cb.is_active = TRUE
            ORDER BY cs.display_order, cb.display_order
        ");
        $stmt->bindParam(':pageName', $pageName);
        $stmt->execute();
        
        $content = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $content[] = $row;
        }
        
        return $content;
    }
    
    public function updateContentBlock($blockId, $newContent) {
        $stmt = $this->conn->prepare("
            UPDATE content_blocks 
            SET content = :content, updated_at = CURRENT_TIMESTAMP
            WHERE block_id = :blockId
        ");
        $stmt->bindParam(':content', $newContent);
        $stmt->bindParam(':blockId', $blockId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function getAllServices() {
        $stmt = $this->conn->query("
            SELECT s.*, sd.short_description, sd.full_description
            FROM services s
            LEFT JOIN service_details sd ON s.service_id = sd.service_id AND sd.language = 'ru'
            WHERE s.is_active = TRUE
            ORDER BY s.display_order
        ");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateServicePrice($serviceId, $newPrice) {
        $stmt = $this->conn->prepare("
            UPDATE services 
            SET base_price = :price, updated_at = CURRENT_TIMESTAMP
            WHERE service_id = :serviceId
        ");
        $stmt->bindParam(':price', $newPrice);
        $stmt->bindParam(':serviceId', $serviceId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

class ActivityTracker {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function startSession($visitorId, $ip, $userAgent, $referrer, $landingPage) {
        $deviceType = $this->detectDeviceType($userAgent);
        
        // Создаем или обновляем посетителя
        $stmt = $this->conn->prepare("
            INSERT INTO visitors (visitor_id, ip_address, user_agent, device_type)
            VALUES (:visitorId, :ip, :userAgent, :deviceType)
            ON CONFLICT (visitor_id) DO UPDATE SET 
                last_visit = CURRENT_TIMESTAMP,
                ip_address = EXCLUDED.ip_address,
                user_agent = EXCLUDED.user_agent,
                device_type = EXCLUDED.device_type
        ");
        $stmt->bindParam(':visitorId', $visitorId);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':userAgent', $userAgent);
        $stmt->bindParam(':deviceType', $deviceType);
        $stmt->execute();
        
        // Создаем новую сессию
        $sessionId = bin2hex(random_bytes(32));
        $stmt = $this->conn->prepare("
            INSERT INTO user_sessions 
            (session_id, visitor_id, referrer_url, landing_page)
            VALUES (:sessionId, :visitorId, :referrer, :landingPage)
        ");
        $stmt->bindParam(':sessionId', $sessionId);
        $stmt->bindParam(':visitorId', $visitorId);
        $stmt->bindParam(':referrer', $referrer);
        $stmt->bindParam(':landingPage', $landingPage);
        $stmt->execute();
        
        return $sessionId;
    }
}

// Дополнительные helper-функции
function getPageContent($pageName) {
    $manager = new ContentManager(getDBConnection());
    return $manager->getPageContent($pageName);
}

function getAllServices() {
    $manager = new ContentManager(getDBConnection());
    return $manager->getAllServices();
}
?>