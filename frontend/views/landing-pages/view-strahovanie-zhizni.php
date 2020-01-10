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
                Защитите близких
                */ ?>
                <?= ShortCode::widget(['shortCode' => 'h1', 'sprintf' => '<h1>%s</h1>']) ?>
            </div>
            <div class="col-12 col-md-10 mx-auto text-center">
                <?php /*
                Размер страховой выплаты будет равен фактической задолженности застрахованного заёмщика
                —&nbsp;КСЖ&nbsp;«Евразия» полностью погасит кредит при&nbsp;наступлении страхового случая.
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
                    <img src="https://eurasia-life.com/tmp/landing-3.jpg"
                         id="pano-img"
                         alt="<?= Html::encode($this->title) ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <?php /*
        header: Преимущества
        - - -
        <div>
            <svg width="46" height="56" xmlns="http://www.w3.org/2000/svg"><path d="M28.75 0h-23C2.587 0 .029 2.52.029 5.6L0 50.4C0 53.48 2.559 56 5.721 56H40.25c3.163 0 5.75-2.52 5.75-5.6V16.8L28.75 0zm5.75 44.8h-23v-5.6h23v5.6zm0-11.2h-23V28h23v5.6zm-8.625-14V4.2l15.813 15.4H25.874z" fill="#3AAB47" fill-rule="nonzero"></path></svg>
            <span class="_">Гарантия погашения кредита</span>
        </div>
        - - -
        <div>
            <svg width="54" height="65" xmlns="http://www.w3.org/2000/svg"><path d="M27 0L0 11.818v17.727C0 45.943 11.52 61.277 27 65c15.48-3.723 27-19.057 27-35.455V11.818L27 0zm-6 47.273L9 35.455l4.23-4.166L21 38.91l19.77-19.47L45 23.636 21 47.273z" fill="#3AAB47" fill-rule="nonzero"></path></svg>
            <span class="_">Защита прав наследников в&nbsp;случае с&nbsp;залоговым кредитом.</span>
        </div>
        */ ?>
        <?php
        $block1Header = ShortCode::get('block-1__header');
        $block1Content1 = ShortCode::get('block-1__content-1');
        $block1Content2 = ShortCode::get('block-1__content-2');
        ?>
        <?php if ($block1Header): ?>
            <div class="row my-4 benefits benefits_left">
                <div class="col-12 text-center">
                    <h2><?= $block1Header ?></h2>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <div class="benefits__item">
                        <?= $block1Content1 ?>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <div class="benefits__item">
                        <?= $block1Content2 ?>
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
                Страховой полис может приобрести любое физическое лицо, взявшее кредит, кроме лиц, страдающих психическими,
                тяжёлыми неврологическими, онкологическими заболеваниями, СПИДом или заболеваниями сердечно-сосудистой системы,
                а&nbsp;также инвалидов I, II&nbsp;и&nbsp;III группы и&nbsp;лиц, достигших возраста 75&nbsp;лет
            </li>
            <li>
                Договор вступает в&nbsp;силу в&nbsp;первый рабочий день, следующий за&nbsp;днем его заключения
            </li>
            <li>
                При наступлении страхового случая АО&nbsp;«КСЖ «Евразия» будет погашать взносы по&nbsp;кредиту
                за&nbsp;Страхователя
            </li>
        </ul>

        title: Какие риски покрывает договор
        - - - - -
        content:
        <ul class="osns-about-list__inner">
            <li>Смерть по&nbsp;любой причине (кроме исключений по&nbsp;страховым рискам)</li>
            <li>Смерть в&nbsp;результате несчастного случая</li>
        </ul>
        */ ?>
        <?php
        $title1 = ShortCode::get('block-2__title-1');
        $content1 = ShortCode::get('block-2__content-1');
        $title2 = ShortCode::get('block-2__title-2');
        $content2 = ShortCode::get('block-2__content-2');
        ?>
        <?php if (($title1 && $content1) || ($title2 && $content2)): ?>
            <div class="row">
                <div class="col-12 col-md-8 mx-auto mt-md-4">
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


