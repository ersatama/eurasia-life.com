<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;

$appAssetBaseUrl = AppAsset::register($this)->baseUrl;
$appAssetBasePath = AppAsset::register($this)->basePath;

$this->params['body-class'] = 'error-500';

// ставить код выше!
$this->beginContent('@app/views/layouts/base.php', [
    'isMainPage' => false,
]);

?>
<div class="inifinity">&nbsp;</div>

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
