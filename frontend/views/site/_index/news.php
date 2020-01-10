<?php

/* @var $this yii\web\View */
/* @var $postArticles common\models\PostArticle[] */

use common\helpers\Html;

if (!$postArticles) {
    return '';
}

?>
<section class="container mb-5">
    <h2 class="text-center mb-4"><?= Yii::t('app', 'Новости компании') ?></h2>
    <div class="row mb-4">
        <?php foreach ($postArticles as $postArticle): ?>
            <?php
            $postArticleName = Html::encode($postArticle->name);
            $thumbUrl = $postArticle->thumbUrl;
            ?>
            <div class="col-12 col-md-4">
                <p>
                    <small class="gray">
                        <?= Html::dateString($postArticle->publish_at) ?>
                    </small>
                </p>
                <p>
                    <a href="<?= $postArticle->url ?>">
                        <?php if ($thumbUrl): ?>
                            <img src="<?= $thumbUrl ?>"
                                 class="w-100 mb-2"
                                 alt="<?= $postArticleName ?>"
                                 title="<?= $postArticleName ?>">
                        <?php endif; ?>
                        <?= $postArticleName ?>
                    </a>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="font-weight-bold">
        <?= Html::a(Yii::t('app', 'Архив новостей'), ['posts/index']) ?>
    </div>
</section>
