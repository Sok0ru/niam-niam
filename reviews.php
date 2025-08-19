<?php
require_once 'includes/header.php';

// Здесь можно добавить получение отзывов из базы данных
?>

<section id="reviews" class="reviews-section bg-dark">
    <div class="container">
        <h2 class="section-subtitle">Рецензии</h2>
        <h1 class="section-title-reviews">Оценки клиентов о нашей работе</h1>
        
        <div class="reviews-grid">
            <!-- Отзыв 1 -->
            <div class="review-card">
                <div class="review-header">
                    <div class="review-avatar">А</div>
                    <div class="review-author">
                        <div class="dark">
                        <h3>Ангелина</h3>
                      </div>
                        <div class="review-rating">★★★★★</div>
                    </div>
                </div>
                <div class="review-content">
                    <p>Что же, подчеркивает систему и отличается каждым. Потом специалисты в ваших регистрах. Социальные учеты могут изменяться как не только по текущей модели, но и при помощи качества и качества клиентов.</p>
                    <p class="review-date">12 мая 2024</p>
                </div>
            </div>
            
            <!-- Отзыв 2 -->
            <div class="review-card">
                <div class="review-header">
                    <div class="review-avatar">И</div>
                    <div class="review-author">
                      <div class="dark">
                        <h3>Иван</h3>
                      </div>
                        
                        <div class="review-rating">★★★★☆</div>
                    </div>
                </div>
                <div class="review-content">
                    <p>Между Вами уже предоставлены все вопросы, которые вы можете найти для вас. После этой информации вы будете выполнены работу данных. Чтобы вы получили возможность получения данных.</p>
                    <p class="review-date">3 апреля 2024</p>
                </div>
            </div>
            
            <!-- Отзыв 3 -->
            <div class="review-card">
                <div class="review-header">
                    <div class="review-avatar">П</div>
                    <div class="review-author">
                        <div class="dark">
                        <h3>Петр</h3>
                      </div>
                        <div class="review-rating">★★★★★</div>
                    </div>
                </div>
                <div class="review-content">
                    <p>Прочная модель способствует вам выполнить новый и более высокий процесс очень хорошего времени. Прочным образом можно выполнить работу. Определите способа Рекомендуем к управленческой программе.</p>
                    <p class="review-date">21 марта 2024</p>
                </div>
            </div>
        </div>
        
        <!-- Блок с отзывами с других площадок -->
        <div class="external-reviews">
            <h2 class="external-title">Отзывы с других площадок</h2>
            <div class="review-platform">
                <div class="platform-rating">
                    <span class="rating-value">4.9</span>
                    <span class="rating-stars">★★★★★</span>
                    <span class="rating-count">(128 отзывов)</span>
                </div>
                <a href="#" class="review-link" onclick="trackExternalReviewsClick()">Читать все отзывы →</a>
            </div>
        </div>
    </div>
</section>

<script>
function trackExternalReviewsClick() {
    if (window.tracker) {
        window.tracker.trackAction('external_reviews_click', event.target, {
            platform: 'external',
            page: 'reviews'
        });
    }
    // Здесь можно открыть ссылку на внешний ресурс
    alert('Переход на внешний ресурс с отзывами');
}
</script>

<?php require_once 'includes/footer.php'; ?>