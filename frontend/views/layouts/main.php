<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use frontend\assets\AppAsset;

$isMainPage = isset($this->params['isMainPage']) && $this->params['isMainPage'];
$appAssetBaseUrl = AppAsset::register($this)->baseUrl;
$appAssetBasePath = AppAsset::register($this)->basePath;

// ставить код выше!
$this->beginContent('@app/views/layouts/base.php', [
    'isMainPage' => $isMainPage,
]);

?>

<?= $this->render('main-header', [
    'isMainPage' => $isMainPage,
]) ?>

<div class="page-content">
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

<?= $this->render('main-footer', [
    'isMainPage' => $isMainPage,
    'appAssetBaseUrl' => $appAssetBaseUrl,
]) ?>

<?= $this->render('main-callback') ?>

<?= $this->render('main-jivosite') ?>

<?php $this->endContent(); ?>
