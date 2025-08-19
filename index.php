<?php
require_once 'includes/header.php';

// Получаем услуги из базы данных
$services = $contentManager->getAllServices();
?>

<section class="about-section bg-yellow">
    <div class="container">
        <div class="about-header-container">
            <div class="header-text">
                <div class="text1">Широкий спектр строительных услуг</div>
                <h2>
                    РЕМОНТ ПОД КЛЮЧ<br>
                    ОТДЕЛКА ДОМА <br>
                    КАЧЕСТВЕННЫЙ РЕМОНТ
                </h2>
                <div class="text1" style="padding-top: 30px;">
                    Мы справимся с любыми объектами. Воплотим в жизнь любые
                    <br>ваши идеи по проектированию дома и обустройству квартиры
                </div>
                <button type="button" class="cta-button" onclick="trackCtaClick('main_button')">Отправить заявку</button>
            </div>
            <div class="LogoItem">
                <img src="assets/images/Logo.png" alt="Логотип компании">
            </div>
        </div>
    </div>
</section>

<!-- Секция "О компании" (белый фон) -->
<section id="about" class="about-section bg-white">
    <div class="container-advantages">
        <h2 class="section-title">Почему мы держимся на плаву все 10+ лет?</h2>

        <div class="slider" data-slider>
            <div class="slides" data-slides>
                <div class="slide"><img src="assets/images/Logo.png" alt="Пример работы 1"></div>
                <div class="slide"><img src="assets/images/Pic2.png" alt="Пример работы 2"></div>
                <div class="slide"><img src="assets/images/Pic3.png" alt="Пример работы 3"></div>
            </div>
            <button class="prev" data-prev>&#10094;</button>
            <button class="next" data-next>&#10095;</button>
        </div>

        <!--Почему именно мы-->
        <div class="advantages-grid">
            <div class="advantage-card">
                <h3>Профессионализм</h3>
                <p>На балконе, в котором подробно оценивается с каждого места отключения. Мы узнаем, что вы находитесь в условиях отделения каких-то дней.</p>
            </div>
            <div class="advantage-card">
                <h3>Качество</h3>
                <p>Уже давно формируемся с помощью информации, приводя к себе задачу, которая должна быть связана с системой организации.</p>
            </div>
            <div class="advantage-card">
                <h3>Надежность</h3>
                <p>На балконе, в котором подробно оценивается с каждого места отключения. Мы узнаем, что вы находитесь в условиях отделения каких-то дней.</p>
            </div>
        </div>
    </div>
</section>

<!-- Секция с ценами на услуги -->
<section class="pricing-section bg-black">
    <div class="container">
        <h2 class="price-title">Стоимость услуг</h2>
        
        <div class="pricing-column">
            <?php if (!empty($services)): ?>
                <?php foreach (array_slice($services, 0, 2) as $service): ?>
                <div class="pricing-card">
                    <div class="price-header">
                        <span class="price-from">ОТ</span>
                        <span class="price-amount"><?= number_format($service['base_price'], 0, ',', ' ') ?></span>
                        <span class="price-currency"> руб.</span>
                    </div>
                    <div class="service-info">
                        <div class="service-name"><?= htmlspecialchars($service['service_name']) ?></div>
                        <div class="service-price"><?= htmlspecialchars($service['short_description'] ?? 'Описание') ?></div>
                    </div>
                    <button type="button" class="cta-button-price" onclick="trackServiceClick(<?= $service['service_id'] ?>)">
                        ЗАКАЗАТЬ
                    </button>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Заглушка если нет услуг -->
                <div class="pricing-card">
                    <div class="price-header">
                        <span class="price-from">ОТ</span>
                        <span class="price-amount">50 000</span>
                        <span class="price-currency"> руб.</span>
                    </div>
                    <div class="service-info">
                        <div class="service-name">Первая услуга</div>
                        <div class="service-price">Описание</div>
                    </div>
                    <button type="button" class="cta-button-price">ЗАКАЗАТЬ</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Остальные секции остаются без изменений -->
<section id="steps" class="steps-section bg-white">
    <div class="container">
        <h2 class="section-title">Как мы работаем</h2>
        <div class="work-steps">
            <div class="step">
                <div class="step-number2">1</div>
                <div class="step-content">
                    <h3>Заявка</h3>
                    <p>Оставьте заявку на нашем сайте или по телефону</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number2">2</div>
                <div class="step-content">
                    <h3>Подтверждение</h3>
                    <p>Менеджер свяжется с вами для уточнения деталей</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number2">3</div>
                <div class="step-content">
                    <h3>Строительство</h3>
                    <p>Выполнение работ</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number2">4</div>
                <div class="step-content">
                    <h3>Оплата</h3>
                    <p>Вы принимаете работу после проверки и оплачиваете</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Секция контактов (желтый фон) -->
<section id="contact" class="contact-section bg-yellow">
    <div class="container">
        <div class="contact-header">
            <h2>Остались вопросы?</h2>
            <p>Оставьте заявку – вы получите подробный ответ</p>
        </div>
        <form class="contact-form" id="mainContactForm">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Ваше Имя" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                </div>
            </div>
            <div class="form-group">
                <textarea name="message" placeholder="Сообщение" rows="5"></textarea>
            </div>
            <button type="submit" class="cta-button">ОТПРАВИТЬ ЗАЯВКУ</button>
        </form>
    </div>
</section>

<!-- Секция "Чем мы гордимся" (серый фон) -->
<section id="stats" class="stats-section bg-gray">
    <div class="container">
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

<section id="reviews-main" class="reviews-section-main bg-gray">
    <div class="container">
        <h1 class="section-title-reviews-main">Отзывы о нас</h1>
        
        <div class="reviews-grid-main">
            <!-- Отзыв 1 -->
            <div class="review-card-main">
                <div class="review-header">
                    <div class="review-avatar">А</div>
                    <div class="review-author-dark">
                        <h3>Ангелина</h3>
                        <div class="review-rating">★★★★★</div>
                    </div>
                </div>
                <div class="review-content-main">
                    <p>Что же, подчеркивает систему и отличается каждым. Потом специалисты в ваших регистрах. Социальные учеты могут изменяться как не только по текущей модели, но и при помощи качества и качества клиентов.</p>
                    <p class="review-date">12 мая 2024</p>
                </div>
            </div>
            
            <!-- Отзыв 2 -->
            <div class="review-card-main">
                <div class="review-header">
                    <div class="review-avatar">И</div>
                    <div class="review-author-dark">
                        <h3>Иван</h3>
                        <div class="review-rating">★★★★☆</div>
                    </div>
                </div>
                <div class="review-content-main">
                    <p>Между Вами уже предоставлены все вопросы, которые вы можете найти для вас. После этой информации вы будете выполнены работу данных. Чтобы вы получили возможность получения данных.</p>
                    <p class="review-date">3 апреля 2024</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Функции для трекинга
function trackCtaClick(buttonName) {
    if (window.tracker) {
        window.tracker.trackAction('cta_click', document.querySelector('.cta-button'), {
            button: buttonName,
            location: 'main_section'
        });
    }
    // Показываем форму контактов
    document.getElementById('contact').scrollIntoView({behavior: 'smooth'});
}

function trackServiceClick(serviceId) {
    if (window.tracker) {
        window.tracker.trackAction('service_click', event.target, {
            service_id: serviceId,
            page: 'home'
        });
    }
    // Перенаправляем на страницу услуг
    window.location.href = 'services.php';
}

// Обработка формы
document.getElementById('mainContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (window.tracker) {
        window.tracker.trackAction('form_submit', this, {
            form_type: 'main_contact',
            form_id: 'mainContactForm'
        });
    }
    
    // Здесь будет отправка формы на сервер
    alert('Заявка отправлена! Мы свяжемся с вами в ближайшее время.');
    this.reset();
});
</script>

<?php require_once 'includes/footer.php'; ?>