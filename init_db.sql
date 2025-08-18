-- Основные таблицы контента
CREATE TABLE content_pages (
    page_id INT AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL UNIQUE,
    page_title VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE content_sections (
    section_id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT NOT NULL,
    section_name VARCHAR(100) NOT NULL,
    section_key VARCHAR(50) NOT NULL UNIQUE,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (page_id) REFERENCES content_pages(page_id)
);

CREATE TABLE content_blocks (
    block_id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    block_type ENUM('title', 'subtitle', 'text', 'image', 'price') NOT NULL,
    content TEXT NOT NULL,
    meta_data JSON,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES content_sections(section_id)
);

-- Таблицы услуг
CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_key VARCHAR(50) NOT NULL UNIQUE,
    base_price DECIMAL(10, 2) NOT NULL,
    price_description VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE service_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    language VARCHAR(10) DEFAULT 'ru',
    short_description VARCHAR(255),
    full_description TEXT,
    FOREIGN KEY (service_id) REFERENCES services(service_id)
);

-- Пользователи и активность
CREATE TABLE visitors (
    visitor_id VARCHAR(32) PRIMARY KEY,
    first_visit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_visit TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    device_type ENUM('desktop', 'mobile', 'tablet', 'bot')
);

CREATE TABLE user_sessions (
    session_id VARCHAR(64) PRIMARY KEY,
    visitor_id VARCHAR(32) NOT NULL,
    referrer_url VARCHAR(255),
    landing_page VARCHAR(255) NOT NULL,
    session_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    session_end TIMESTAMP NULL,
    FOREIGN KEY (visitor_id) REFERENCES visitors(visitor_id)
);

CREATE TABLE page_views (
    view_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    page_id INT NOT NULL,
    view_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    time_spent INT COMMENT 'Время на странице в секундах',
    scroll_depth INT COMMENT 'Процент прокрутки страницы',
    FOREIGN KEY (session_id) REFERENCES user_sessions(session_id),
    FOREIGN KEY (page_id) REFERENCES content_pages(page_id)
);

CREATE TABLE user_actions (
    action_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    action_type ENUM('click', 'form_submit', 'phone_call', 'service_click') NOT NULL,
    element_id VARCHAR(50),
    element_text VARCHAR(255),
    action_data JSON,
    action_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES user_sessions(session_id)
);

-- Контакты и заявки
CREATE TABLE contact_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_id VARCHAR(32),
    name VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    message TEXT,
    request_source ENUM('main_form', 'callback', 'service_page') NOT NULL,
    page_url VARCHAR(255),
    status ENUM('new', 'processed', 'completed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visitor_id) REFERENCES visitors(visitor_id)
);

-- Отзывы
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_id VARCHAR(32),
    author_name VARCHAR(100) NOT NULL,
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    content TEXT NOT NULL,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visitor_id) REFERENCES visitors(visitor_id)
);