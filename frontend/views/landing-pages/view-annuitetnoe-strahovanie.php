<?php

/**
 * @var $this yii\web\View
 * @var $landingPage common\models\LandingPage
 * @var $requestForm frontend\models\RequestForm
 */

use common\helpers\Html;
use common\packages\htmlhead\HtmlHead;
use frontend\widgets\ShortCode;

// todo: .user-content ?

$h1 = ShortCode::get('h1');
$h1Sub = ShortCode::get('h1-sub');

$this->title = $h1;
$htmlHead = new HtmlHead();
$htmlHead->description = html_entity_decode(strip_tags($h1Sub));
$htmlHead->openGraph->changeTypeToArticle();
?>

<section class="landing-page">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <?php /*
                Выплаты работнику и&nbsp;его семье
                */ ?>
                <?= ShortCode::widget(['shortCode' => 'h1', 'sprintf' => '<h1>%s</h1>']) ?>
            </div>
            <div class="col-12 col-md-10 mx-auto text-center">
                <?php /*
                При наступлении страхового случая по&nbsp;договору <a href="">ОСНС</a>&nbsp;&mdash; КСЖ &laquo;Евразия&raquo; выплатит утраченный доход работника
                */ ?>
                <?= ShortCode::widget(['shortCode' => 'h1-sub', 'sprintf' => '<p class="landing-page__subheader">%s</p>']) ?>
            </div>
            <div class="col-12 mt-3 text-center">
                <a href="#form" class="btn btn_a scroll-to"><?= Yii::t('app', 'Оставить заявку') ?></a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 round-up mt-4 panorama-warp">
                <div class="panorama">
                    <img src="https://eurasia-life.com/tmp/landing-2.jpg"
                         id="pano-img"
                         alt="<?= Html::encode($this->title) ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php /*
        Выплаты производятся по&nbsp;договорам ОСНС, заключённым с&nbsp;КСЖ&nbsp;«Евразия», в&nbsp;рамках Закона РК «Об&nbsp;обязательном страховании работника от&nbsp;несчастных случаев при&nbsp;исполнении им&nbsp;трудовых&nbsp;(служебных) обязанностей»
        */ ?>
        <?php ShortCode::beginShortCode('block-1') ?>
        <div class="ro">
            <div class="col-12 col-md-10 mx-auto mt-4">
                <p class="text-center">
                    %s
                </p>
            </div>
        </div>
        <?php ShortCode::end() ?>


        <?php /*
        header: Выгоды
        - - - - -
        <div>
            <svg width="61" height="74" xmlns="http://www.w3.org/2000/svg"><path d="M30.5 0L0 13.455v20.181C0 52.305 13.013 69.762 30.5 74 47.987 69.762 61 52.305 61 33.636V13.455L30.5 0zm-6.778 53.818L10.167 40.364l4.778-4.743 8.777 8.678 22.333-22.166 4.778 4.776-27.11 26.91z" fill="#3AAB47" fill-rule="nonzero"></path></svg><br>
            Защита имущественных интересов работника и&nbsp;его семьи
        </div>
        - - -
        <div>
            <svg width="77" height="77" xmlns="http://www.w3.org/2000/svg"><path d="M38.5 0C17.248 0 0 17.248 0 38.5S17.248 77 38.5 77 77 59.752 77 38.5 59.752 0 38.5 0zm13.898 24.409a7.41 7.41 0 0 1 7.431 7.43 7.41 7.41 0 0 1-7.43 7.431 7.41 7.41 0 0 1-7.431-7.43c-.039-4.12 3.311-7.431 7.43-7.431zm-23.1-6.083c5.006 0 9.087 4.081 9.087 9.086s-4.081 9.086-9.086 9.086-9.087-4.081-9.087-9.086a9.054 9.054 0 0 1 9.087-9.086zm0 35.15v14.438c-9.24-2.888-16.554-10.01-19.788-19.096 4.042-4.312 14.129-6.506 19.788-6.506 2.041 0 4.62.308 7.316.846-6.314 3.35-7.315 7.777-7.315 10.319zM38.5 69.3c-1.04 0-2.04-.038-3.041-.154v-15.67c0-5.466 11.319-8.2 16.94-8.2 4.119 0 11.242 1.502 14.784 4.427C62.678 61.139 51.55 69.3 38.5 69.3z" fill="#3AAB47" fill-rule="nonzero"></path></svg><br>
            Социальная ответственность работодателя перед&nbsp;работниками
        </div>
        - - -
        <div>
            <svg width="71" height="67" xmlns="http://www.w3.org/2000/svg"><path d="M67.263 55.833v3.723C67.263 63.65 63.9 67 59.79 67H7.474C3.326 67 0 63.65 0 59.556V7.444C0 3.35 3.326 0 7.474 0h52.315c4.111 0 7.474 3.35 7.474 7.444v3.723H33.632c-4.148 0-7.474 3.35-7.474 7.444V48.39c0 4.094 3.326 7.444 7.474 7.444h33.631zM33.632 48.39H71V18.61H33.632V48.39zm14.947-9.306c-3.102 0-5.605-2.494-5.605-5.583 0-3.09 2.503-5.583 5.605-5.583 3.102 0 5.605 2.494 5.605 5.583 0 3.09-2.503 5.583-5.605 5.583z" fill="#3AAB47" fill-rule="nonzero"></path></svg><br>
            Гарантия быстрой страховой выплаты
        </div>
        */ ?>
        <?php
        $block2Header = ShortCode::get('block-2__header');
        $block2Content1 = ShortCode::get('block-2__content-1');
        $block2Content2 = ShortCode::get('block-2__content-2');
        $block2Content3 = ShortCode::get('block-2__content-3');
        ?>
        <?php if ($block2Header): ?>
            <div class="row text-center my-4 benefits">
                <div class="col-12">
                    <h2><?= $block2Header ?></h2>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="benefits__item">
                        <?= $block2Content1 ?>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="benefits__item">
                        <?= $block2Content2 ?>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="benefits__item">
                        <?= $block2Content3 ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <?php /*
        title: Условия заключения договора
        - - - - -
        content:
        <ul class="osns-about-list__inner">
            <li>
                договор обязательного страхования работника от несчастного случая может оформить любой работодатель или&nbsp;его представитель
            </li>
            <li>
                для&nbsp;расчёта страховой премии и&nbsp;страховой суммы необходимо предоставить штатное расписание с&nbsp;указанием суммы годового фонда оплаты труда всех работников
            </li>
            <li>
                страховой тариф зависит от&nbsp;вида экономической деятельности, класса профессионального риска и&nbsp;статистики несчастных случаев на&nbsp;производстве
            </li>
        </ul>


        title: Какие риски покрывает договор
        - - - - -
        content:
        <ul class="osns-about-list__inner">
            <li>Утрата трудоспособности</li>
            <li>Профессиональное заболевание</li>
            <li>Смерть</li>
        </ul>
        */ ?>
        <?php
        $title1 = ShortCode::get('block-3__title-1');
        $content1 = ShortCode::get('block-3__content-1');
        $title2 = ShortCode::get('block-3__title-2');
        $content2 = ShortCode::get('block-3__content-2');
        ?>
        <?php if (($title1 && $content1) || ($title2 && $content2)): ?>
            <div class="row">
                <div class="col-12 col-md-8 mx-auto mt-3">
                    <ul class="list-unstyled osns-about-list" id="osns-about-list">
                        <?php if ($title1 && $content1): ?>
                            <li :class="{'osns-about-list_open': lists[1]}">
                                <a href="" @click.prevent="toggleList(1)"><?= $title1 ?></a>
                                <div v-if="lists[1]"><?= $content1 ?></div>
                            </li>
                        <?php endif; ?>

                        <?php if ($title2 && $content2): ?>
                            <li :class="{'osns-about-list_open': lists[2]}">
                                <a href="" @click.prevent="toggleList(2)"><?= $title2 ?></a>
                                <div v-if="lists[2]"><?= $content2 ?></div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="green-bg round-top round-bottom">
    <div class="container">
        <div class="row my-5 request-wrap" id="form">
            <div class="col-12 col-md-8 pt-4 order-2 order-md-1">
                <h2><?= Yii::t('app', 'Заявка на&nbsp;страховку') ?></h2>
                <p>
                    <?= Yii::t('app', 'Наш оператор перезвонит, расскажет об&nbsp;условиях и&nbsp;поможет оформить договор.') ?>
                </p>
                <?= $this->render('light-form', [
                    'requestForm' => $requestForm,
                ]) ?>
            </div>
            <?php /*
            Преимущество КСЖ «Евразия» — быстрая гарантированная выплата без занижения суммы страхового возмещения
            */ ?>
            <?php ShortCode::beginShortCode('form-factoid') ?>
            <div class="col-12 col-md-4 order-1 order-md-2">
                <div class="mediator">
                    <span class="mediator__text">%s</span><br>
                    <?= $this->render('view-form-icon') ?>
                </div>
            </div>
            <?php ShortCode::end() ?>
        </div>
    </div>
</section>

