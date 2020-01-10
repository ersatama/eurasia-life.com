<?php

/**
 * @var $this yii\web\View
 * @var $landingPage common\models\LandingPage
 * @var $requestForm frontend\models\RequestForm
 */

use common\helpers\Html;
use common\packages\htmlhead\HtmlHead;
use frontend\widgets\ShortCode;
use frontend\assets\AppAsset;
use frontend\assets\MultiselectAsset;
use frontend\assets\CalculatorAsset;

$appAssetBaseUrl = AppAsset::register($this)->baseUrl;
MultiselectAsset::register($this);
CalculatorAsset::register($this);

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
                Офис полон опасностей
                */ ?>
                <?= ShortCode::widget(['shortCode' => 'h1', 'sprintf' => '<h1>%s</h1>']) ?>
            </div>

            <div class="col-12 col-md-10 mx-auto text-center">
                <?php /*
                Обязательное страхование работников от&nbsp;несчастных случаев (ОСНС). Быстрые выплаты без занижения
                страховой премии.
                */ ?>
                <?= ShortCode::widget(['shortCode' => 'h1-sub', 'sprintf' => '<p class="landing-page__subheader">%s</p>']) ?>
            </div>

            <div class="col-12 mt-3 text-center">
                <a href="#osns-calculator"
                   class="btn btn_a scroll-to"><?= Yii::t('app', 'Узнать стоимость страховки') ?></a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 round-up mt-4 panorama-warp">
                <div class="panorama">
                    <img src="https://eurasia-life.com/tmp/landing-1.jpg"
                         id="pano-img"
                         alt="<?= Html::encode($this->title) ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <?php /*
        Договор ОСНС
        - - -
        <div class="row">
            <div class="col-12 mb-3">
                <small class="gray">Согласно статье 230 КоАП РК</small>
                <h3 class="mt-2">Работа без договора ОСНС — штраф до 2,5 млн тенге</h3>
            </div>
            <div class="col-12 col-md-4">
                <div class="penalty-info__item">
                    <span class="penalty-info__mrp">160 МРП</span>
                    <span class="penalty-info__tg">404 000 тг</span>
                    <p class="penalty-info__text">
                        на должностных лиц, субъектов малого предпринимательства
                        или некоммерческие организации
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="penalty-info__item">
                    <span class="penalty-info__mrp">400 МРП</span>
                    <span class="penalty-info__tg">1 010 000 тг</span>
                    <p class="penalty-info__text">
                        на субъектов среднего предпринимательства
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="penalty-info__item">
                    <span class="penalty-info__mrp">1000 МРП</span>
                    <span class="penalty-info__tg">2 525 000 тг</span>
                    <p class="penalty-info__text">
                        на субъектов крупного предпринимательства
                    </p>
                </div>
            </div>
        </div>
        - - -
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="penalty-info__item">
                    <span class="penalty-info__mrp">Страхование работников</span>
                    <span class="penalty-info__profit">Cнижение риска судебных разбирательств</span>
                    <p class="penalty-info__text">
                        Если вы&nbsp;не&nbsp;обеспечили страховку, сотрудник, пострадавший в&nbsp;результате
                        несчастного
                        случая, может подать в&nbsp;суд. И&nbsp;суд встанет на&nbsp;его сторону,
                        поскольку
                        уклонение
                        работодателя от&nbsp;заключения договора обязательного страхования&nbsp;&mdash;
                        нарушение
                        закона.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="penalty-info__item">
                    <span class="penalty-info__mrp">Заключение договора ОСНС</span>
                    <span class="penalty-info__profit">Выигрыш в&nbsp;борьбе  за&nbsp;специалистов</span>
                    <p class="penalty-info__text">
                        Гарантируя заключение страхового договора, вы&nbsp;повышаете привлекательность
                        вашей
                        компании
                        для потенциальных сотрудников, заручаетесь их&nbsp;поддержкой и&nbsp;лояльностью,
                        обеспечивая
                        лучшие условия, чем конкуренты.
                    </p>
                </div>
            </div>
        </div>
        */ ?>
        <?php
        $header = ShortCode::get('block-1-header');
        $content1 = ShortCode::get('block-1__content-1');
        $content2 = ShortCode::get('block-1__content-2');
        ?>
        <?php if ($header): ?>
            <div class="row" id="osns-penalty">
                <div class="col-12 mt-3">
                    <h2><?= $header ?></h2>
                </div>
                <div class="col-12 mt-3 osns-toggle-wrap">
                    <ul class="list-inline osns-toggle">
                        <li class="list-inline-item">
                            <span class="osns-toggle__no"
                                  :class="{active: noOsns}"
                                  @click="toggleOsns(true)"><?= Yii::t('app', 'нет') ?></span>
                        </li><li class="list-inline-item">
                            <span class="osns-toggle__yes"
                                  :class="{active: !noOsns}"
                                  @click="toggleOsns(false)"><?= Yii::t('app', 'есть') ?></span>
                        </li>
                    </ul>
                </div>
                <div class="col-12 penalty-info pt-4 pb-3">
                    <div class="penalty-info__no-policy" v-if="noOsns"><?= $content1 ?></div>
                    <div class="penalty-info__yes-policy" v-if="!noOsns"><?= $content2 ?></div>
                </div>
            </div>
        <?php endif; ?>


        <?php /*
        title: Условия заключения договора
        - - - - -
        content:
        <ul class="osns-about-list__inner">
            <li>договор обязательного страхования работника от несчастного случая может оформить любой
                работодатель или его представитель
            </li>
            <li>для расчёта страховой премии и страховой суммы необходимо предоставить штатное
                расписание с
                указанием суммы годового фонда оплаты труда всех работников
            </li>
            <li>страховой тариф зависит от вида экономической деятельности, класса профессионального
                риска и
                статистики несчастных случаев на производстве
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
        $title1 = ShortCode::get('block-2__title-1');
        $content1 = ShortCode::get('block-2__content-1');
        $title2 = ShortCode::get('block-2__title-2');
        $content2 = ShortCode::get('block-2__content-2');
        $title3 = ShortCode::get('block-2__title-3');
        $content3 = ShortCode::get('block-2__content-3');
        ?>
        <?php if (($title1 && $content1) || ($title2 && $content2) || ($title3 && $content3)): ?>
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
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
                        <?php if ($title3 && $content3): ?>
                            <li :class="{'osns-about-list_open': lists[3]}">
                                <a href="" @click.prevent="toggleList(3)"><?= $title3 ?></a>
                                <div v-if="lists[3]"><?= $content3 ?></div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<script>
    let i18n = {
        a1: '<?= Yii::t('app', 'Расчет стоимости') ?>',
        a2: '<?= Yii::t('app', 'Вид деятельности по&nbsp;ОКЭД') ?>',
        a3: '<?= Yii::t('app', 'Сотрудники с&nbsp;зарплатой более 425&nbsp;000&nbsp;тг/мес.') ?>',
        a4: '<?= Yii::t('app', 'Годовой фонд оплаты труда') ?>',
        a5: '<?= Yii::t('app', 'Сотрудники с&nbsp;зарплатой 425&nbsp;000&nbsp;тг/мес. и&nbsp;меньше') ?>',
        a6: '<?= Yii::t('app', 'были страховые случаи в&nbsp;последние 5&nbsp;лет') ?>',
        a7: '<?= Yii::t('app', 'Кол-во пострадавших в&nbsp;среднем за&nbsp;год') ?>',
        a10: '<?= Yii::t('app', 'Cтоимость страховки') ?>',
        a11: '<?= Yii::t('app', 'Страховое покрытие — до&nbsp;5&nbsp;000&nbsp;000&nbsp;тг на&nbsp;человека') ?>',
        a12: '<?= Yii::t('app', 'Оставить заявку') ?>',
        a13: '<?= Yii::t('app', 'Перезвоним в течение 10 минут.') ?>'
    };
</script>
<section class="green-bg round-top round-bottom">
    <div class="container">
        <div class="row my-5 calculator-wrap" id="osns-calculator">
            <div class="col-12 col-md-8 order-2 order-md-1" id="calculator">
                <calculator csrf="<?= Yii::$app->request->csrfToken; ?>" form-name="<?= $requestForm->formName() ?>"></calculator>
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
