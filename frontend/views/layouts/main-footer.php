<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $appAssetBaseUrl string */

use common\helpers\Html;
use frontend\assets\AppAsset;

$appAssetBaseUrl = AppAsset::register($this)->baseUrl;

?>
<!-- .footer -->
<footer class="footer text-center text-md-left mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12<?= $isMainPage ? ' col-md-8' : '' ?>">
                <p class="copyright">
                    <?= Yii::t('app', '©&nbsp;АО&nbsp;«Компания по&nbsp;страхованию жизни «Евразия»') ?>
                </p>
                <ul class="list-inline mb-1">
                    <li class="list-inline-item my-2">
                        <?= Html::a(Yii::t('app', 'Новости'), ['posts/index']) ?>
                    </li>

                    <?= $this->render('main-footer_pages') ?>

                    <li class="list-inline-item d-block d-md-inline-block my-2">
                        <address class="m-0">
                            <a href="https://yandex.kz/maps/-/CCulR68L" target="_blank">
                                <svg width="12" height="18" xmlns="http://www.w3.org/2000/svg"><path d="M6 1.5c2.397 0 4.5 1.963 4.5 4.202 0 2.32-1.87 5.348-4.5 9.495-2.63-4.147-4.5-7.176-4.5-9.495C1.5 3.463 3.603 1.5 6 1.5zM6 0C2.852 0 0 2.552 0 5.702 0 8.85 2.602 12.609 6 18c3.398-5.391 6-9.15 6-12.298C12 2.552 9.15 0 6 0zm0 8.25a2.25 2.25 0 1 1 0-4.5 2.25 2.25 0 0 1 0 4.5z" fill="#3AAB47"/></svg>
                                <?= Yii::t('app', 'Ул. Желтоксан, 59, Алматы') ?>
                            </a>
                        </address>
                    </li>
                    <li class="list-inline-item d-block d-md-inline-block my-2">
                        <a href="https://www.facebook.com/Eurasia-Life-Insurance-Company-2276336165939708/" target="_blank" class="mr-1"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 0H5a5 5 0 0 0-5 5v14a5 5 0 0 0 5 5h14a5 5 0 0 0 5-5V5a5 5 0 0 0-5-5zm-3 7h-1.924C13.461 7 13 7.252 13 7.889V9h3l-.238 3H13v8h-3v-8H8V9h2V7.077C10 5.055 11.064 4 13.461 4H16v3z" fill="#3AAB47" fill-rule="nonzero"/></svg></a>
                        <a href="https://www.instagram.com/eurasia_life_insurance_company/" target="_blank"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" fill="#3AAB47" fill-rule="nonzero"/></svg></a>
                    </li>
                </ul>
                <p class="pt-3 gray small">
                    <?= Yii::t('app', 'Лицензия №&nbsp;2.2.50 от&nbsp;4 марта 2019&nbsp;года на&nbsp;право осуществления страховой (перестраховочной) деятельности по&nbsp;отрасли «страхование жизни»') ?>
                </p>
            </div>
            <?php if ($isMainPage): ?>
                <div class="col-12 col-md-4">
                    <div class="footer__grafica text-left gray">
                        <a href="http://grafica.kz" target="_blank" class="float-left mr-2">
                            <img src="<?= $appAssetBaseUrl ?>/i/grafica.svg" alt="Графика">
                        </a>
                        <?= Yii::t('app', 'Сайт сделан<br>в&nbsp;студии&nbsp;&laquo;Графика&raquo;') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?= $this->render('main-counters') ?>
        </div>
    </div>
</footer>
