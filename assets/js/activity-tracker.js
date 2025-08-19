class ActivityTracker {
    constructor() {
        this.sessionId = null;
        this.visitorId = this.getVisitorId();
        this.lastActivity = Date.now();
        this.scrollDepth = 0;
        this.currentPage = window.location.pathname;
        
        this.initSession();
        this.setupEventListeners();
        this.trackPageView();
    }
    
    initSession() {
        fetch('/api/track.php?action=session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                visitorId: this.visitorId,
                referrer: document.referrer || 'direct',
                landingPage: window.location.pathname
            })
        })
        .then(response => response.json())
        .then(data => {
            this.sessionId = data.sessionId;
        });
    }
    trackPageView() {
        if (!this.sessionId) return;
        
        const pageTitle = document.title;
        const pageUrl = window.location.pathname;
        
        // Отправка данных при загрузке страницы
        fetch('/api/track/pageview', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sessionId: this.sessionId,
                pageUrl,
                pageTitle
            })
        });
        
        // Отслеживание времени на странице
        window.addEventListener('beforeunload', () => {
            const timeSpent = Math.floor((Date.now() - this.lastActivity) / 1000);
            const scrollDepth = this.scrollDepth;
            
            navigator.sendBeacon('/api/track/pageleave', JSON.stringify({
                sessionId: this.sessionId,
                pageUrl,
                timeSpent,
                scrollDepth
            }));
        });
    }
    
    trackAction(actionType, element, extraData = {}) {
        if (!this.sessionId) return;

        const elementId = element.id || null;
        const elementText = element.textContent.trim().substring(0, 250);

        fetch('/api/track.php?action=event', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sessionId: this.sessionId,
                actionType,
                elementId,
                elementText,
                actionData: extraData
            })
        });
    }
    
    setupEventListeners() {
        // Отслеживание кликов по кнопкам и ссылкам
        document.addEventListener('click', (e) => {
            const target = e.target.closest('a, button, .cta-button, .service-card');
            if (!target) return;
            
            let actionType = 'click';
            if (target.classList.contains('cta-button')) actionType = 'cta_click';
            if (target.classList.contains('service-card')) actionType = 'service_click';
            
            this.trackAction(actionType, target);
        });
        
        // Отслеживание отправки форм
        document.addEventListener('submit', (e) => {
            const form = e.target;
            this.trackAction('form_submit', form, {
                formId: form.id,
                formAction: form.action
            });
        });
        
        // Отслеживание скролла
        window.addEventListener('scroll', () => {
            const scrollPercent = Math.round(
                (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
            );
            if (scrollPercent > this.scrollDepth) {
                this.scrollDepth = scrollPercent;
            }
        });
    }
    
    getVisitorId() {
        let visitorId = localStorage.getItem('visitorId');
        if (!visitorId) {
            visitorId = 'vis_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('visitorId', visitorId);
        }
        return visitorId;
    }
    
    startActivityMonitoring() {
        setInterval(() => {
            this.lastActivity = Date.now();
        }, 5000);
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    window.activityTracker = new ActivityTracker();
});

console.log('ActivityTracker: инициализирован');

// Тестовая функция для проверки
window.testTracker = function() {
    console.log('Тест трекера:');
    console.log('Visitor ID:', window.tracker.visitorId);
    console.log('Session ID:', window.tracker.sessionId);
    
    // Тестовое событие
    window.tracker.trackAction('test', document.body, {test: true});
    console.log('Тестовое событие отправлено');
};
