<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $pageForm backend\models\PageForm */

use common\helpers\Html;
use common\widgets\ActiveForm;
use backend\models\PageForm;

$pageId = $pageForm->getPage()->id;
$isCreateForm = $pageForm->scenario === PageForm::SCENARIO_CREATE;
?>
<?php $form = ActiveForm::begin([
    'scrollToErrorOffset' => 100,
]); ?>

<?= $form->notifies($pageForm) ?>

<?php if (!$isCreateForm): ?>
    <?= $form->field($pageForm, 'id')->staticControl() ?>
<?php endif; ?>

<?= $form->field($pageForm, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($pageForm, 'slug')
    ->textInput(['maxlength' => true])
    ->widget('backend\widgets\SlugField') ?>

<?= $form->field($pageForm, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($pageForm, 'body')
    ->textarea(['maxlength' => true, 'rows' => 20])
    ->widget('backend\widgets\ImperaviRedactorWidget') ?>

<?php if (($pageData = $pageForm->getPageData())): ?>
    <?= $form
        ->field($pageForm, 'position_type')
        ->inline()
        ->radioList($pageForm->getPositionTypeData()) ?>

    <?= $form
        ->field($pageForm, 'position_id')
        ->inline()
        ->dropDownList($pageData, ['prompt' => 'укажите модель связи']) ?>
<?php endif; ?>

<?= $form->field($pageForm, 'visible')->checkbox() ?>

<div class="form-group fixed-form-btn-blk">
    <?= Html::submitButton(
        $isCreateForm ? 'Добавить' : 'Сохранить',
        ['class' => 'btn btn-success btn-lg btn-block w-md btn-bordered waves-effect waves-light']
    ) ?>

    <?php if (!$isCreateForm): ?>
        <div class="link-blk">
            <a href="<?= $pageForm->getPage()->fullUrlSimSim ?>" target="_blank">Страница на&nbsp;сайте</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!$isCreateForm): ?>
    <?= $form->field($pageForm, 'views')->staticControl() ?>
<?php endif; ?>

<?php ActiveForm::end(); ?>
