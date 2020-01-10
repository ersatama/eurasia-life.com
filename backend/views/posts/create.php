<?php

/* @var $this yii\web\View */
/* @var $languages common\models\Language[] */
/* @var $postArticleForm backend\models\PostArticleForm */

use common\helpers\Html;

$this->title = 'Добавить новую новость';
?>
<div class="p-20">
    <p>←&nbsp;<?= Html::a('Вернуться к списку новостей', ['index']) ?></p>
    <?= $this->render('_form', [
        'languages' => $languages,
        'postArticleForm' => $postArticleForm,
    ]) ?>
</div>
