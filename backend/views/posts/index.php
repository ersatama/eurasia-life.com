<?php

/* @var $this yii\web\View */
/* @var $posts common\models\PostArticle[] */
/* @var $postArticleSearchForm backend\models\PostArticleSearchForm */

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\Notify;
use backend\widgets\Move;

$searchQ = $postArticleSearchForm->q;

$this->title = 'Новости';
?>
<div class="p-20">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Notify::widget() ?>
    <p><?= Html::a('Добавить новую новость', ['create'], ['class' => 'text-success']) ?></p>
    <?php if (($posts = $postArticleSearchForm->dataProvider->models)): ?>
        <div class="custom-dd-empty dd <?= $searchQ ? 'without-handler' : '' ?>" id="page-list"
             data-url="<?= Url::to(['move']) ?>">
            <ol class="dd-list">
                <?php foreach ($posts as $post): ?>
                    <li class="dd-item dd3-item" data-id="<?= $post->id ?>">

                        <?php if (!$searchQ): ?>
                            <div class="dd-handle dd3-handle"></div>
                        <?php endif; ?>

                        <div class="dd3-content">
                            <?php
                            $name = Html::encode($post->name);
                            if ($searchQ) {
                                $name = preg_replace(
                                    sprintf('/(%s)/uis', preg_quote($searchQ, '/')),
                                    '<span class="search-text">$1</span>',
                                    $name
                                );
                            }
                            ?>
                            <?= Html::a(
                                $name,
                                ['update', 'id' => $post->id],
                                ['class' => ($post->visible ? null : 'text-muted')]
                            ) ?>
                        </div>
                    </li>
                <?php endforeach ?>
            </ol>
        </div>
        <?= LinkPager::widget(['pagination' => $postArticleSearchForm->dataProvider->pagination]) ?>
    <?php else: ?>
        <p>Новости не найдены.</p>
    <?php endif; ?>
</div>

<?php Move::widget(['selector' => '#page-list']) ?>
