<?php
require_once 'includes/header.php';
?>

<section class="about-section bg-yellow">
    <div class="container-head">
        <div class="about-header-container">
            <div class="header-text">
                <div class="text1">О компании</div>
                <h2>
                    РЕМОНТ ПОД КЛЮЧ<br>
                    ОТДЕЛКА ДОМА <br>
                    КАЧЕСТВЕННЫЙ РЕМОНТ
                </h2>
            </div>
            <div class="LogoItem">
                <img src="assets/images/Logo.png" alt="Логотип компании">
            </div>
        </div>
    </div>
</section>

<section class="about2-section bg-white">
    <div class="container">
        <div class="about-header-container">
            <div class="about-content">
                <div class="about-text">
                    <p>Команда профессионалов с более чем <span class="textcolor">10-летним опытом</span> выполняющая ремонт квартир, коттеджей, офисных и торговых помещений <span class="textcolor">«под ключ».</span> Мы искусно совмещаем современные технологии отделки и надёжные традиционные методы, чтобы каждый наш проект стал образцом <span class="textcolor">качества и комфорта</span></p>
                </div>
                
                <div class="about-contacts">
                    <h4>Контакты</h4>
                    <div class="contact-item">
                        <span>Руководитель</span>
                        <span>+7 967 484-66-22</span>
                    </div>
                    <div class="contact-item">
                        <span>Зам-руководителя</span>
                        <span>+7 123 456-78-90</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Преимущества -->
<section id="advantages" class="advantages-section bg-white">
    <div class="container-advantages">
        <h2 class="section-title">Наши преимущества</h2>
        <div class="advantages-grid">
            <div class="advantage-card">
                <img src="assets/images/Pic1.png" class="advantage-icon">
                <h3>СКОРОСТЬ</h3>
                <p>Выполняем все быстро, без задержек, большой командой специалистов</p>
            </div>
            <div class="advantage-card">
                <img src="assets/images/Pic2.png" class="advantage-icon">
                <h3>КАЧЕСТВО</h3>
                <p>Делаем все на совесть, только проверенными и качественными материалами!</p>
            </div>
            <div class="advantage-card">
                <img src="assets/images/Pic3.png" class="advantage-icon">
                <h3>СПРАВЕДЛИВЫЕ ЦЕНЫ</h3>
                <p>Цены всегда ниже рынка. Свои поставщики материалов.</p>
            </div>
        </div>
    </div>
</section>

<!-- Как мы работаем -->
<section id="work-steps" class="steps-section bg-gray">
    <div class="container-work">
        <h2 class="section-title">Как мы работаем</h2>
        
        <div class="work-steps">
            <div class="step">
                <div class="step-number2">1</div>
                <div class="step-content">
                    <h3 class="step-title">Заявка</h3>
                    <p class="step-description">Оставьте заявку на нашем сайте или по телефону</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number2">2</div>
                <div class="step-content">
                    <h3 class="step-title">Подтверждение</h3>
                    <p class="step-description">Менеджер свяжется с вами для уточнения деталей</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number2">3</div>
                <div class="step-content">
                    <h3 class="step-title">Строительство</h3>
                    <p class="step-description">Выполнение работ согласно договору</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number2">4</div>
                <div class="step-content">
                    <h3 class="step-title">Оплата</h3>
                    <p class="step-description">Вы принимаете работу после проверки и оплачиваете</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Статистика -->
<section class="stats-section bg-grey">
    <div class="container">
        <h2 class="section-title light-text">Чем мы гордимся</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">1600+</div>
                <div class="stat-text">Построенных объектов</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-text">Лет в строительной сфере</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">40+</div>
                <div class="stat-text">Видов услуг</div>
            </div>
        </div>
    </div>
</section>

<!-- Контакты -->
<section id="about-contacts" class="contact-section bg-yellow">
    <div class="container">
        <h2 class="section-title">Остались вопросы?</h2>
        <p class="contact-subtitle">Оставьте заявку – вы получите подробный ответ</p>
        
        <div class="contact-form">
            <form id="aboutContactForm">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Ваше Имя" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="+7 (___)-___-__-__" required>
                    </div>
                </div>
                    
                <div class="form-group">
                    <textarea name="message" placeholder="Сообщение" rows="5"></textarea>
                </div>
                
                <button type="submit" class="cta-button">Отправить заявку</button>
            </form>
        </div>
    </div>
</section>

<script>
// Обработка формы контактов
document.getElementById('aboutContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (window.tracker) {
        window.tracker.trackAction('form_submit', this, {
            form_type: 'about_contact',
            form_id: 'aboutContactForm',
            page: 'about'
        });
    }
    
    alert('Заявка отправлена! Мы свяжемся с вами в ближайшее время.');
    this.reset();
});
</script>

<?php require_once 'includes/footer.php'; ?>