<?php

/* @var $this \yii\web\View */

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\Script;

if (!isset($this->params['header-request-form']) || !$this->params['header-request-form']) {
    return '';
}

/**
 * @var $requestForm \frontend\models\RequestForm
 */
$requestForm = $this->params['header-request-form'];

?>

<div class="modal fade modal-transparent" id="callback" tabindex="-1" role="dialog" aria-labelledby="delivery">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Заказ звонка') ?></h4>
                <button type="button" class="close mt-1" data-dismiss="modal" aria-label="Close">&nbsp;</button>
            </div>
            <div class="modal-body">

                <?php ActiveForm::begin(['options' => ['id' => 'callback-form']]); ?>

                <?= Html::activeTextInput($requestForm, 'name', [
                    'class' => 'form-control',
                    'placeholder' => $requestForm->getAttributeLabel('name'),
                    'required' => true,
                    'maxlength' => true,
                ]) ?>

                <?= Html::activeInputTel($requestForm, 'phone', [
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>

                <button class="btn btn_a px-0" type="submit"><?= Yii::t('app', 'Заказать') ?></button>

                <div class="help-block mt-2" style="display: none"><span class="text-success"></span></div>

                <?php ActiveForm::end(); ?>

                <p>
                    <?= Yii::t('app', 'Перезвоним вам в ближайшее время.') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php Script::begin() ?>
<script>
    (function () {
        var $form = $('#callback-form');
        $form.on('beforeSubmit', function (e) {
            $.post($form.attr('action'), $form.serializeArray(), function (result) {
                if (result.message) {
                    var $block = $form.find('.help-block');
                    $block.find('span').html(result.message);
                    $block.show();
                    $form.next('p').hide();
                }
            }).fail(function () {
                var $block = $form.find('.help-block');
                $block.find('span')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html('Произошла ошибка. Попробуйте позже еще раз');
                $block.show();
            });
            $form.find('input,button').attr({disabled: true});
            return false;
        });
    })();
</script>
<?php Script::end() ?>
