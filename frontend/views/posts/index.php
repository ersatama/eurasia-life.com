<?php

/* @var $this yii\web\View */
/* @var $postArticleSearchForm frontend\models\PostArticleSearchForm */

use common\helpers\Html;
use common\packages\htmlhead\HtmlHead;
use frontend\widgets\PostListView;

$this->title = 'Новости КСЖ «Евразия»';
$htmlHead = new HtmlHead();
?>
<div class="container">
    <?= $this->render('_breadcrumbs', ['postArticle' => null]) ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= PostListView::widget([
        'dataProvider' => $postArticleSearchForm->dataProvider,
    ]) ?>
</div>
