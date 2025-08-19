<?php
require_once 'includes/db-connect.php';
require_once 'includes/functions.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitles = [
    'index.php' => 'Главная страница',
    'about.php' => 'О компании', 
    'services.php' => 'Услуги',
    'reviews.php' => 'Отзывы'
];
$pageTitle = $pageTitles[$currentPage] ?? 'ТольяттиРемонт';

// Получаем данные из БД
$contentManager = new ContentManager(getDBConnection());
$mainContent = $contentManager->getPageContent('home');
?>
<!DOCTYPE html>
<html lang="ru" data-page="<?= str_replace('.php', '', $currentPage) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | ТольяттиРемонт</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Шапка (желтая) -->
    <header class="header-section bg-yellow">
        <div class="container-head">
            <div class="header-content">
                <div class="logo">
                    <img src="assets/images/Logo.png" alt="Логотип" width="80">
                    <a href="index.php">ТольяттиРемонт</a>
                </div>
                <nav>
                    <ul class="nav-links">
                        <li><a href="about.php">О компании</a></li>
                        <li><a href="services.php">Услуги</a></li>
                        <li><a href="about.php#work-steps">Этапы работы</a></li>
                        <li><a href="reviews.php">Отзывы</a></li>
                        <li><a href="about.php#about-contacts">Контакты</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>