<?php

/* @var $this yii\web\View */
/* @var $postArticles common\models\PostArticle[] */

use common\packages\htmlhead\HtmlHead;
use common\helpers\Html;
use frontend\widgets\ShortCode;

$this->params['isMainPage'] = true;
//$this->params['body-class'][] = 'main-page';

$this->title = '«Евразия» – страхование жизни, ОСНС, пенсионный аннуитет';
$htmlHead = new HtmlHead([
    'description' => 'Компания по страхованию жизни «Евразия» гарантирует страховые выплаты без занижения суммы страхового возмещения',
]);
?>

<div class="container text-center mb-4">
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-11 col-lg-8">
            <?php /*
            Гарантируем страховые выплаты
            */ ?>
            <?= ShortCode::widget(['shortCode' => 'h1', 'sprintf' => '<h1>%s</h1>']) ?>

            <?php /*
            без занижения суммы страхового возмещения
            */ ?>
            <?= ShortCode::widget(['shortCode' => 'h1-sub', 'sprintf' => '<p class="subheader">%s</p>']) ?>
        </div>
    </div>
</div>

<?= $this->render('_index/landing-pages') ?>

<section class="container mb-5 main-page__about">
    <div class="row justify-content-md-center mb-5">
        <div class="col-12 col-md-10 col-lg-8 pt-5">
            <?php /*
            КСЖ&nbsp;«Евразия», дочерняя компания лидера рынка страхования по&nbsp;объёму страховых
            выплат и&nbsp;премий —&nbsp;СК&nbsp;«Евразия», работающей в&nbsp;Казахстане уже более 20&nbsp;лет,
            —&nbsp;предлагает страхование работников от&nbsp;несчастных случаев, пенсионный аннуитет
            и&nbsp;страхование жизни заёмщиков по&nbsp;кредитам
            */ ?>
            <?= ShortCode::widget(['shortCode' => 'block-1', 'sprintf' => '<p class="text-center">%s</p>']) ?>
        </div>
    </div>
    <?php /*
    Рейтинги финансовой надёжности СК «Евразия»
    */ ?>
    <?php ShortCode::beginShortCode('block-2__header') ?>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-11 text-center">
            <h3>%s</h3>
        </div>
    </div>
    <?php ShortCode::end() ?>
    <div class="row">
        <?php /*
        <img src="/static/tmp-003.png" alt="BBB-">
        <h3 class="d-inline-block ml-4">
            BBB-
            <small class="d-block gray">Прогноз стабильный</small>
        </h3>
        <p class="mt-4">
            14 ноября 2018 года ведущее международное рейтинговое агентство
            Standard & Poor’s присвоило АО «Страховая компания «Евразия»
            кредитный рейтинг BBB- (прогноз стабильный) и рейтинг
            финансовой надёжности на уровне kzAAA.
            Впервые в истории финансового рынка Республики Казахстан
            отечественному частному финансовому институту присвоен
            рейтинг инвестиционной категории.
        </p>
        */ ?>
        <?php ShortCode::beginShortCode('block-2__content-1') ?>
        <div class="col-12 col-md-6 mb-2 inter-rating__col">
            <div class="inter-rating__item">
                %s
            </div>
        </div>
        <?php ShortCode::end() ?>

        <?php /*
        <img src="/static/tmp-002.png" alt="B++">
        <h3 class="d-inline-block ml-4">
            B++
            <small class="d-block gray">Прогноз стабильный</small>
        </h3>
        <p class="mt-4">
            На 29 июня 2018 года АО «Страховая компания «Евразия» имеет
            кредитный рейтинг BBB+ от ведущего международного рейтингового
            агентства A.M. Best и рейтинг финансовой устойчивости
            на уровне B++ (прогноз стабильный).
        </p>
        */ ?>
        <?php ShortCode::beginShortCode('block-2__content-2') ?>
        <div class="col-12 col-md-6 mb-2 inter-rating__col">
            <div class="inter-rating__item">
                %s
            </div>
        </div>
        <?php ShortCode::end() ?>
    </div>
</section>

<?= $this->render('_index/news', [
    'postArticles' => $postArticles,
]) ?>
