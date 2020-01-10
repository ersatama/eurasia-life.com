<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $postArticleForm backend\models\PostArticleForm */

use common\helpers\Html;
use backend\models\PostArticleForm;

$postArticleId = $postArticleForm->postArticle->id;
$isCreateForm = $postArticleForm->scenario === PostArticleForm::SCENARIO_CREATE;

?>

<?= $form->field($postArticleForm, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($postArticleForm, 'slug')
    ->textInput(['maxlength' => true])
    ->widget('backend\widgets\SlugField') ?>

<div class="upload-image">
    <?php ob_start(); ?>
    <?php if (($image = $postArticleForm->postArticle->mainImage) && ($imageUrl = $image->fullUrl)): ?>
        <div class="upload-image__preview-blk m-b-10">
            <a href="<?= $imageUrl ?>" target="_blank" class="upload-image__link">
                <img src="<?= $image->previewFullUrl ?>" alt="" class="upload-image__image">
            </a>
            <button type="submit"
                    name="<?= Html::getInputName($postArticleForm, 'btn_remove_image') ?>"
                    value="main"
                    class="btn btn-danger btn-xs m-t-10"
                    data-confirm="Вы действительно хотите удалить главную картинку?">Удалить
            </button>
        </div>
    <?php endif; ?>
    <?php $buf = ob_get_clean(); ?>

    <?php ob_start(); ?>
    <?= $form->field($postArticleForm, 'main_image_url')->textInput(['maxlength' => true]) ?>
    <?php $buf2 = ob_get_clean(); ?>

    <?= $form
        ->field($postArticleForm, 'main_image', [
            'template' => "{label}\n{$buf}\n{input}\n{hint}\n{error} или $buf2",
        ])
        ->fileInput([
            'accept' => implode(',', PostArticleForm::MAIN_IMAGE_TYPES),
            'class' => 'filestyle',
            'data-buttonText' => 'Выбрать' . (trim($buf) ? ' новую' : '') . ' картинку',
            'data-input' => 'false',
        ]) ?>
</div>

<?php /*/ ?>
<?= $form->field($postArticleForm, 'announce')
    ->textarea(['maxlength' => true, 'rows' => 20])
    ->widget('backend\widgets\ImperaviRedactorWidget') ?>
<?php /*/ ?>

<?= $form->field($postArticleForm, 'content')
    ->textarea(['maxlength' => true, 'rows' => 20])
    ->widget('backend\widgets\ImperaviRedactorWidget') ?>

<div class="row">
    <div class="col-xs-12 col-sm-2">
        <?= $form->field($postArticleForm, 'publish_date')->textInput()
            ->widget('kartik\widgets\DatePicker', [
                'type' => 1,
                'options' => [
                    'autocomplete' => 'off',
                ],
                'pluginOptions' => [
                    'todayBtn' => 'linked',             // показываем "Сегодня"
                    'language' => 'ru',                 // язык
                    'daysOfWeekHighlighted' => '0,6',   // подсветка выходных
                    'autoclose' => true,                // закрываем после выбора даты
                    'todayHighlight' => true,           // подсветка сегод. даты
                    'maxViewMode' => 2,                 // макс. показываем года
                ],
            ]) ?>
    </div>
    <div class="col-xs-12 col-sm-2">
        <?= $form->field($postArticleForm, 'publish_time')->textInput(['autocomplete' => 'off'])
            ->widget('backend\widgets\TimepickerWidget', [
                'pluginOptions' => [
                    'showMeridian' => false,
                ],
            ]) ?>
    </div>
</div>

<?= $this->render('/_parts/form-seo-fields', [
    'form' => $form,
    'model' => $postArticleForm,
]) ?>

<?= $form->field($postArticleForm, 'visible')->checkbox() ?>

<?php if (!$isCreateForm): ?>
    <?= $form->field($postArticleForm, 'id')->staticControl() ?>
    <?= $form->field($postArticleForm, 'views')->staticControl() ?>
<?php endif; ?>
