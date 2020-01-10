<?php

/* @var $this yii\web\View */
/* @var $requestForm frontend\models\RequestForm */

use common\helpers\Html;
use common\widgets\ActiveForm;

if (!$requestForm) {
    return '';
}
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'form-inline',
    ],
]) ?>

<?= $form->notifies($requestForm) ?>

<?php /* Куда записываемся */ ?>
<?= $form->field($requestForm, 'group', [
    'labelOptions' => [
        'class' => 'control-label mb-2 sr-only',
    ],
])->dropDownList($requestForm->getGroupData(), [
    'class' => 'form-control mb-2',
//    'required' => true,
]) ?>

<?php /* Имя */ ?>
<?= $form->field($requestForm, 'name', [
    'labelOptions' => [
        'class' => 'control-label mb-2 sr-only',
    ],
])->textInput([
    'placeholder' => 'Как вас зовут',
    'maxlength' => true,
    'class' => 'form-control mb-2',
//    'required' => true,
]) ?>

<?php /* Телефон */ ?>
<?= $form->field($requestForm, 'phone', [
    'labelOptions' => [
        'class' => 'control-label mb-2 sr-only',
    ],
])->input('tel', [
    'placeholder' => 'Ваш номер телефона',
    'maxlength' => true,
    'class' => 'form-control mb-2',
//    'required' => true,
]) ?>

<?php /* Отправить */ ?>
<?= Html::submitButton('Отправить заявку', [
    'class' => 'btn btn-a mb-2',
]) ?>

<?php ActiveForm::end(); ?>
