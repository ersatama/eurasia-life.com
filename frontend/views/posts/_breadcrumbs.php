<?php

use common\helpers\Html;
use frontend\widgets\Breadcrumbs;

/**
 * @var $this yii\web\View
 * @var $postArticle common\models\PostArticle|null
 */

$breadcrumbs = [
    [
        'label' => Yii::t('app', 'Главная'),
        'url' => ['site/index', 'language' => Yii::$app->language],
    ],
    [
        'label' => Yii::t('app', 'Новости'),
        'url' => isset($postArticle) && $postArticle ? ['posts/index'] : null,
    ],
];

if (isset($postArticle) && $postArticle) {
    $breadcrumbs[] = [
        'label' => Html::encode($postArticle->name),
    ];
}

echo Breadcrumbs::widget([
    'links' => $breadcrumbs,
    'encodeLabels' => false,
    'homeLink' => false,
]);
