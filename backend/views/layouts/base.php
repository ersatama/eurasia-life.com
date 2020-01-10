<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\helpers\Html;

$bodyClass = isset($this->params['body-class']) ? sprintf(' %s', $this->params['body-class']) : '';
$htmlClass = isset($this->params['html-class']) ? sprintf(' class="%s"', $this->params['html-class']) : '';

$this->beginPage();
?><!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"<?= $htmlClass ?>>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/favicon.ico">
    <title><?= Html::encodeTitle($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="fixed-left<?= $bodyClass ?>">
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
