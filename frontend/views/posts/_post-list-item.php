<?php

/* @var $this yii\web\View */
/* @var $model common\models\PostArticle */
/* @var $key string|int */
/* @var $index int */
/* @var $widget frontend\widgets\PostListView */

use yii\helpers\Url;
use common\helpers\Html;

/**
 * @var $models common\models\PostArticle[];
 */
$models = $widget->dataProvider->getModels();
$prevArticle = $models[--$index] ?? null;
$article = $model;

$title = Html::encode($article->name);
$url = Url::to(['posts/view', $article]);
$thumbUrl = $article->thumbUrl;

$dateFormat = 'd.m.Y';
$dateString = date($dateFormat, $article->publish_at);
//if ($prevArticle && date($dateFormat, $prevArticle->publish_at) == $dateString) {
//    $dateString = '';
//}
if ($dateString === date($dateFormat)) {
    $dateString = 'Сегодня';
}

?>

<p>
    <small class="gray">
        <?= $dateString ?>
    </small>
</p>
<p>
    <a href="<?= $url ?>">
        <?php if ($thumbUrl): ?>
            <img src="<?= $thumbUrl ?>"
                 class="w-100 mb-2"
                 alt="<?= $title ?>"
                 title="<?= $title ?>">
        <?php endif; ?>
        <?= $title ?>
    </a>
</p>