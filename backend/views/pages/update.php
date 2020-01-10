<?php

/* @var $this yii\web\View */
/* @var $pageForm \backend\models\PageForm */

use common\helpers\Html;
use common\widgets\Notify;

$this->title = 'Редактирование страницы';
?>
<div class="p-20">
    <p>←&nbsp;<?= Html::a('Вернуться к списку страниц', ['index']) ?></p>
    <h1 class="m-b-20"><?= Html::encode($this->title) ?></h1>
    <?= Notify::widget() ?>
    <?= $this->render('_form', [
        'pageForm' => $pageForm,
    ]) ?>
    <div>
        <hr>
        <h2>Удалить страницу</h2>
        <?= Html::beginForm(['delete', 'id' => $pageForm->getPage()->id])
        . Html::submitButton('Удалить страницу',
            [
                'class' => 'btn btn-danger btn-sm w-md btn-bordered waves-effect waves-light',
                'data-confirm' => 'Вы действительно хотите удалить текущую страницу?',
            ])
        . Html::endForm() ?>
    </div>
</div>
