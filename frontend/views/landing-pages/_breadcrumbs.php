<?php

use common\helpers\Html;
use frontend\widgets\Breadcrumbs;

/**
 * @var $this yii\web\View
 * @var $landingPage common\models\LandingPage
 */


$breadcrumbs = [
    [
        'label' => Yii::t('app', 'Главная'),
        'url' => ['site/index', 'language' => Yii::$app->language],
    ],
    [
        'label' => Html::encode($landingPage->name),
    ],
];

echo Breadcrumbs::widget([
    'links' => $breadcrumbs,
    'encodeLabels' => false,
    'homeLink' => false,
]);
