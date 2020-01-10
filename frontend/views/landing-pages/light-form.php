<?php

/**
 * @var $this yii\web\View
 * @var $requestForm frontend\models\RequestForm
 */

use common\helpers\Html;
use common\widgets\ActiveForm;

// Заключить договор
// Заказать страховку

if (!$requestForm) {
    return '';
}
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'form-inline request-form',
    ],
]) ?>

<?= $form->notifies($requestForm) ?>

<div class="row form-wrap">
    <div class="col-12 col-md-6">
        <?php /* Телефон */ ?>
        <?= $form->field($requestForm, 'phone', [
            'labelOptions' => [
                'class' => 'control-label mb-2 sr-only',
            ],
        ])->input('tel', [
            'placeholder' => '+7 (___) ___-__-__',
            'maxlength' => true,
            'class' => 'form-control mb-md-2 request-form__phone',
        //    'required' => true,
        ]) ?>
    </div>
    <div class="col-12 col-md-6">
        <?php /* Отправить */ ?>
        <?= Html::submitButton(Yii::t('app','Отправить заявку'), [
            'class' => 'btn btn_a mb-2 px-0 px-md-3 request-form__btn',
        ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
