<?php

/* @var $this yii\web\View */
/* @var $languages common\models\Language[] */
/* @var $postArticleForm backend\models\PostArticleForm */

use common\helpers\Html;

$this->title = 'Редактирование новости';
?>
<div class="p-20">
    <p>←&nbsp;<?= Html::a('Вернуться к списку новостей', ['index']) ?></p>
    <?= $this->render('_form', [
        'languages' => $languages,
        'postArticleForm' => $postArticleForm,
    ]) ?>
    <div>
        <hr>
        <h2>Удалить новость</h2>
        <?= Html::beginForm(['delete', 'id' => $postArticleForm->getPostArticle()->id])
        . Html::submitButton('Удалить новость',
            [
                'class' => 'btn btn-danger btn-sm w-md btn-bordered waves-effect waves-light',
                'data-confirm' => 'Вы действительно хотите удалить текущую новость?',
            ])
        . Html::endForm() ?>
    </div>
</div>
