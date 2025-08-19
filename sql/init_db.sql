DROP TABLE IF EXISTS user_actions CASCADE;
DROP TABLE IF EXISTS page_views CASCADE;
DROP TABLE IF EXISTS user_sessions CASCADE;
DROP TABLE IF EXISTS contact_requests CASCADE;
DROP TABLE IF EXISTS reviews CASCADE;
DROP TABLE IF EXISTS visitors CASCADE;
DROP TABLE IF EXISTS service_details CASCADE;
DROP TABLE IF EXISTS services CASCADE;
DROP TABLE IF EXISTS content_blocks CASCADE;
DROP TABLE IF EXISTS content_sections CASCADE;
DROP TABLE IF EXISTS content_pages CASCADE;
-- Основные таблицы контента
CREATE TABLE content_pages (
    page_id SERIAL PRIMARY KEY,
    page_name VARCHAR(50) NOT NULL UNIQUE,
    page_title VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE content_sections (
    section_id SERIAL PRIMARY KEY,
    page_id INTEGER NOT NULL REFERENCES content_pages(page_id),
    section_name VARCHAR(100) NOT NULL,
    section_key VARCHAR(50) NOT NULL UNIQUE,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE content_blocks (
    block_id SERIAL PRIMARY KEY,
    section_id INTEGER NOT NULL REFERENCES content_sections(section_id),
    block_type VARCHAR(20) CHECK (block_type IN ('title', 'subtitle', 'text', 'image', 'price')),
    content TEXT NOT NULL,
    meta_data JSONB,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблицы услуг
CREATE TABLE services (
    service_id SERIAL PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_key VARCHAR(50) NOT NULL UNIQUE,
    base_price DECIMAL(10, 2) NOT NULL,
    price_description VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE service_details (
    detail_id SERIAL PRIMARY KEY,
    service_id INTEGER NOT NULL REFERENCES services(service_id),
    language VARCHAR(10) DEFAULT 'ru',
    short_description VARCHAR(255),
    full_description TEXT
);

-- Пользователи и активность
CREATE TABLE visitors (
    visitor_id VARCHAR(32) PRIMARY KEY,
    first_visit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_visit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    device_type VARCHAR(10) CHECK (device_type IN ('desktop', 'mobile', 'tablet', 'bot'))
);

CREATE TABLE user_sessions (
    session_id VARCHAR(64) PRIMARY KEY,
    visitor_id VARCHAR(32) NOT NULL REFERENCES visitors(visitor_id),
    referrer_url VARCHAR(255),
    landing_page VARCHAR(255) NOT NULL,
    session_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    session_end TIMESTAMP NULL
);

CREATE TABLE page_views (
    view_id BIGSERIAL PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL REFERENCES user_sessions(session_id),
    page_id INTEGER NOT NULL REFERENCES content_pages(page_id),
    view_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    time_spent INTEGER,
    scroll_depth INTEGER
);

CREATE TABLE user_actions (
    action_id BIGSERIAL PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL REFERENCES user_sessions(session_id),
    action_type VARCHAR(20) CHECK (action_type IN ('click', 'form_submit', 'phone_call', 'service_click')),
    element_id VARCHAR(50),
    element_text VARCHAR(255),
    action_data JSONB,
    action_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Контакты и заявки
CREATE TABLE contact_requests (
    request_id SERIAL PRIMARY KEY,
    visitor_id VARCHAR(32) REFERENCES visitors(visitor_id),
    name VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    message TEXT,
    request_source VARCHAR(20) CHECK (request_source IN ('main_form', 'callback', 'service_page')),
    page_url VARCHAR(255),
    status VARCHAR(20) CHECK (status IN ('new', 'processed', 'completed')) DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Отзывы
CREATE TABLE reviews (
    review_id SERIAL PRIMARY KEY,
    visitor_id VARCHAR(32) REFERENCES visitors(visitor_id),
    author_name VARCHAR(100) NOT NULL,
    rating SMALLINT CHECK (rating BETWEEN 1 AND 5),
    content TEXT NOT NULL,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Триггер для обновления updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_content_pages_updated_at BEFORE UPDATE ON content_pages FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_services_updated_at BEFORE UPDATE ON services FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_content_blocks_updated_at BEFORE UPDATE ON content_blocks FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Индексы для оптимизации
CREATE INDEX idx_visitor_last_visit ON visitors(last_visit);
CREATE INDEX idx_sessions_visitor ON user_sessions(visitor_id);
CREATE INDEX idx_page_views_session ON page_views(session_id);
CREATE INDEX idx_actions_session ON user_actions(session_id);
CREATE INDEX idx_requests_visitor ON contact_requests(visitor_id);
CREATE INDEX idx_requests_status ON contact_requests(status);
CREATE INDEX idx_services_active ON services(is_active, display_order);
