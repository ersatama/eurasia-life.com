<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;

$appAssetBaseUrl = AppAsset::register($this)->baseUrl;
$appAssetBasePath = AppAsset::register($this)->basePath;

$this->params['body-class'] = 'error-404';

// ставить код выше!
$this->beginContent('@app/views/layouts/base.php', [
    'isMainPage' => false,
]);

?>

<span id="four1" class="fly"><img src="<?= $appAssetBaseUrl ?>/i/4-1.svg" alt=""/></span>
<span id="zero" class="fly"><img src="<?= $appAssetBaseUrl ?>/i/0.svg" alt=""/></span>
<span id="man" class="fly"><img src="<?= $appAssetBaseUrl ?>/i/man.png" alt=""/></span>
<span id="four2" class="fly"><img src="<?= $appAssetBaseUrl ?>/i/4-2.svg" alt=""/></span>

<div class="container">
    <div class="row">
        <?= $content ?>
    </div>
</div>

<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                © АО «Компания по страхованию жизни «Евразия»
            </div>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>
