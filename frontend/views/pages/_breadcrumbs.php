<?php

use common\helpers\Html;
use frontend\widgets\Breadcrumbs;

/**
 * @var $this yii\web\View
 * @var $page common\models\Page
 */

if (!isset($this->params['pages']) || !$this->params['pages']) {
    return;
}

/**
 * @var $pages \common\models\Page[]
 */
$pages = $this->params['pages'];

$breadcrumbs = [
    [
        'label' => Yii::t('app', 'Главная'),
        'url' => ['site/index', 'language' => Yii::$app->language]
    ],
];

$current = false;
foreach ($pages as $_page) {
    if ($_page->depth < 2) {
        continue;
    }

    if ($page->isChildOf($_page) || ($current = $page->id == $_page->id)) {
        $breadcrumbs[] = [
            'label' => Html::encode($_page->name),
            'url' => $current ? null : $_page->url,
        ];
    }
}

echo Breadcrumbs::widget([
    'links' => $breadcrumbs,
    'encodeLabels' => false,
    'homeLink' => false,
]);
