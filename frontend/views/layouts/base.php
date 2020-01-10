<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $isMainPage boolean */

use common\helpers\Html;
use frontend\widgets\BodyAttrs;

$this->registerLinkTag([
    'rel' => 'alternate',
    'href' => '/rss',
    'type' => 'application/rss+xml',
    'title' => 'RSS',
]);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" itemscope="itemscope" itemtype="http://schema.org/WebSite">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?= Html::encodeTitle($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <?php include '_icons.php' ?><?php /*/ todo: вынести! /*/ ?>
    <?php include '_analytics.php' ?><?php /*/ todo: вынести! /*/ ?>
</head>
<body<?= BodyAttrs::widget(['isMainPage' => $isMainPage]) ?>>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
