<?php

/**
 * @var $this        yii\web\View
 * @var $landingPage common\models\LandingPage
 * @var $requestForm frontend\models\RequestForm
 */

use common\helpers\Html;
use common\packages\htmlhead\HtmlHead;
use frontend\widgets\ShortCode;
use frontend\assets\AppAsset;
use frontend\assets\CalculatorAnnuitetAsset;

$appAssetBaseUrl = AppAsset::register($this)->baseUrl;
CalculatorAnnuitetAsset::register($this);

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
		<?php /*
            Пожизненная пенсия
            */ ?>
		<?php ShortCode::beginShortCode('h1') ?>
      <div class="col-12 text-center">
        <h1>%s</h1>
      </div>
		<?php ShortCode::end() ?>

		<?php /*
            Пенсионный аннуитет –&nbsp;это договор со&nbsp;страховой компанией, который позволит начать получать пенсию раньше срока.
            */ ?>
		<?php ShortCode::beginShortCode('h1-sub') ?>
      <div class="col-12 col-md-10 mx-auto text-center">
        <p class="landing-page__subheader">
          %s </p>
      </div>
		<?php ShortCode::end() ?>

      <div class="col-12 mt-3 text-center">
        <a href="#form" class="btn btn_a scroll-to"><?=Yii::t('app', 'Оставить заявку')?></a>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-12 round-up mt-4 panorama-warp">
        <div class="panorama">
          <img src="https://eurasia-life.com/tmp/landing-4.jpg"
            id="pano-img"
            alt="<?= Html::encode($this->title) ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="container">
	  <?php /*
        <p>
            Перенесите свои пенсионные накопления в&nbsp;компанию по&nbsp;страхованию жизни
            и&nbsp;купите пенсионный аннуитет.
            Вы начнёте получать выплаты ещё до&nbsp;выхода на&nbsp;пенсию.
        </p>
        <p>
            При этом срок выплат не&nbsp;ограничится суммой накоплений, ведь страховая компания берёт на&nbsp;себя
            риски
            обеспечения вас средствами за&nbsp;счёт инвестиционного дохода.
            То есть, покупая пенсионный аннуитет, вы обеспечиваете себя пожизненной пенсией.
        </p>
        */ ?>
	  <?php ShortCode::beginShortCode('block-1') ?>
    <div class="pension-about px-3 px-md-0">
      <div class="row">
        <div class="col-12 col-md-10 mx-auto mt-4 mb-4">
          %s
        </div>
      </div>
    </div>
	  <?php ShortCode::end() ?>


	  <?php /*
        <h3>Как рано можно выйти на&nbsp;пенсию?</h3>
        <p>
            Оформить пенсионный аннуитет женщины могут в&nbsp;51 год, а&nbsp;мужчины —&nbsp;в&nbsp;55 лет.
        </p>
        - - -
        <h3>Сколько денег должно быть на&nbsp;счете?</h3>
        <p>
            Женщине необходимо накопить 8,8&nbsp;млн тенге, мужчине —&nbsp;6,3&nbsp;млн тенге.
        </p>
        - - -
        <h3>Что делать, если средств на&nbsp;счете не&nbsp;хватает?</h3>
        <p>
            Пополните пенсионный счёт до&nbsp;нужной суммы, если есть финансовая возможность.
        </p>
        - - -
        <h3>Как получать пенсию?</h3>
        <p>
            Страховая компания будет переводить деньги на&nbsp;ваш счёт в&nbsp;банке.
        </p>
        */ ?>

    <div class="row pension-faq">
		<?php for($a = 1; $a <= 4; $a++): ?><?php ShortCode::beginShortCode('block-2__content-' . $a) ?>
          <div class="col-12 col-md-6 mb-3 mb-md-4">
            <div class="pension-faq__item">
              %s
            </div>
          </div>
			<?php ShortCode::end() ?><?php endfor; ?>
    </div>

	  <?php /*
        title: Виды договора пенсионного аннуитета
        - - - - -
        content:
        <ol class="osns-about-list__inner">
            <li>
                Пожизненный договор без гарантированного периода выплат (для тех, у кого нет наследников):
                <ul>
                    <li>
                        Пенсионные накопления, достаточные для заключения договора, переводятся из ЕНПФ в АО «КСЖ «Евразия»
                    </li>
                    <li>
                        Выплаты осуществляются ежемесячно в течение всей жизни страхователя
                    </li>
                </ul>
            </li>
            <li>
                Пожизненный договор с гарантированным периодом выплат (для тех, у кого есть наследники):
                <ul>
                    <li>
                        Пенсионные накопления, достаточные для заключения договора, переводятся из ЕНПФ в АО «КСЖ «Евразия»
                    </li>
                    <li>
                        Выплаты осуществляются ежемесячно в течение всей жизни страхователя
                    </li>
                    <li>
                        В случае смерти страхователя, выплаты по договору осуществляются законному наследнику в течение
                        гарантированного периода
                    </li>
                </ul>
            </li>
        </ol>
        */ ?>
	  <?php
	  $title1 = ShortCode::get('block-3__title-1');
	  $content1 = ShortCode::get('block-3__content-1');
	  ?>
	  <?php if($title1 && $content1): ?>
        <div class="row">
          <div class="col-12 col-md-8 mx-auto">
            <ul class="list-unstyled osns-about-list" id="osns-about-list">
              <li :class="{'osns-about-list_open': lists[1]}">
                <a href="" @click.prevent="toggleList(1)"><?=$title1?></a>
                <div v-if="lists[1]">
					<?=$content1?>
                </div>
              </li>
            </ul>
          </div>
        </div>
	  <?php endif; ?>
  </div>
</section>

<section class="green-bg round-top round-bottom">
  <div class="container">
    <div class="row my-5 request-wrap" id="form">
      <div class="col-12 col-md-8 order-2 order-md-1" id="calculator">
        <calc-annuitet today="<?= date('Y-m-d') ?>" csrf="<?= Yii::$app->request->csrfToken; ?>" form-name="<?= $requestForm->formName() ?>"></calc-annuitet>
      </div>
      <?php
      /**
      <div class="col-12 col-md-8 pt-4 order-2 order-md-1">
        <h2><?=Yii::t('app', 'Заявка на&nbsp;аннуитет')?></h2>
        <p>
			<?=Yii::t('app', 'Наш оператор перезвонит, расскажет об&nbsp;условиях и&nbsp;поможет оформить договор.')?>
        </p>
		  <?=$this->render('light-form',
						   [
							   'requestForm' => $requestForm,
						   ]
		  )?>
      </div>
       */
      ?>

		<?php /*
            Преимущество КСЖ «Евразия» — быстрая гарантированная выплата без занижения суммы страхового возмещения
            */ ?>
		<?php ShortCode::beginShortCode('form-factoid') ?>
      <div class="col-12 col-md-4 order-1 order-md-2">
        <div class="mediator">
          <span class="mediator__text">%s</span><br>
			<?=$this->render('view-form-icon')?>
        </div>
      </div>
		<?php ShortCode::end() ?>
    </div>
  </div>
</section>
