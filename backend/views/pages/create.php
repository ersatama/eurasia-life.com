<?php

/* @var $this yii\web\View */
/* @var $pageForm backend\models\PageForm */

use common\helpers\Html;
use common\widgets\Notify;

$this->title = 'Добавить новую страницу';
?>
<div class="p-20">
    <p>←&nbsp;<?= Html::a('Вернуться к списку страниц', ['index']) ?></p>
    <h1 class="m-b-20"><?= Html::encode($this->title) ?></h1>
    <?= Notify::widget() ?>
    <?= $this->render('_form', [
        'pageForm' => $pageForm,
    ]) ?>
</div>
