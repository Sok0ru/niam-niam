<?php
require_once 'includes/header.php';

// Получаем все услуги из базы данных
$services = $contentManager->getAllServices();
?>

<section class="services-page-section bg-black">
    <h2 class="services-title">Цены на услуги</h2>
    
    <div class="pricing-grid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
            <div class="pricing-item">
                <h3 class="pricing-title"><?= htmlspecialchars($service['service_name']) ?></h3>
                <p class="pricing-description"><?= htmlspecialchars($service['short_description']) ?></p>
                <div class="price-value"><?= number_format($service['base_price'], 0, ',', ' ') ?> руб</div>
                <p class="price-note"><?= htmlspecialchars($service['price_description']) ?></p>
                <a href="#" class="pricing-button" onclick="trackServiceDetailClick(<?= $service['service_id'] ?>)">Подробнее</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Заглушки если нет услуг -->
            <div class="pricing-item">
                <h3 class="pricing-title">Капитальный ремонт</h3>
                <p class="pricing-description">Полный комплекс работ по капитальному ремонту квартиры под ключ</p>
                <div class="price-value">1 000 000 руб</div>
                <p class="price-note">Дополнительные расходы на материалы</p>
                <a href="#" class="pricing-button">Подробнее</a>
            </div>
            
            <div class="pricing-item">
                <h3 class="pricing-title">Косметический ремонт</h3>
                <p class="pricing-description">Обновление интерьера без замены коммуникаций и конструкций</p>
                <div class="price-value">30 000 руб</div>
                <p class="price-note">Дополнительные расходы на материалы</p>
                <a href="#" class="pricing-button">Подробнее</a>
            </div>
            
            <div class="pricing-item">
                <h3 class="pricing-title">Дизайнерский ремонт</h3>
                <p class="pricing-description">Эксклюзивный ремонт по индивидуальному дизайн-проекту</p>
                <div class="price-value">700 000 руб</div>
                <p class="price-note">Дополнительные расходы на материалы</p>
                <a href="#" class="pricing-button">Подробнее</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Акция -->
    <div class="promo-section">
        <h3 class="promo-title">До конца акции осталось:</h3>
        <div class="timer" id="countdown">06:15:00</div>
        <a href="#" class="promo-button" onclick="trackPromoClick()">Рассчитать стоимость</a>
    </div>
</section>

<!-- Секция контактов (желтый фон) -->
<section id="contact" class="contact-section bg-yellow">
    <div class="contact-header">
        <h2>Остались вопросы?</h2>
        <p>Оставьте заявку – вы получите подробный ответ</p>
    </div>
    <form class="contact-form" id="servicesContactForm">
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
</section>

<script>
function trackServiceDetailClick(serviceId) {
    if (window.tracker) {
        window.tracker.trackAction('service_detail_click', event.target, {
            service_id: serviceId,
            page: 'services'
        });
    }
    // Здесь можно показать модальное окно с деталями услуги
    alert('Детальная информация по услуге ID: ' + serviceId);
}

function trackPromoClick() {
    if (window.tracker) {
        window.tracker.trackAction('promo_click', event.target, {
            promo_type: 'timer',
            page: 'services'
        });
    }
    // Прокрутка к форме контактов
    document.getElementById('contact').scrollIntoView({behavior: 'smooth'});
}

// Обработка формы
document.getElementById('servicesContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (window.tracker) {
        window.tracker.trackAction('form_submit', this, {
            form_type: 'services_contact',
            form_id: 'servicesContactForm',
            page: 'services'
        });
    }
    
    alert('Заявка отправлена! Мы свяжемся с вами в ближайшее время.');
    this.reset();
});

// Таймер акции (оставьте ваш существующий код)
</script>

<?php require_once 'includes/footer.php'; ?>