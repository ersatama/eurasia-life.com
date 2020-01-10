<?php

/* @var $this yii\web\View */
/* @var $form \common\widgets\ActiveForm */
/* @var $shortCodeForm \backend\models\ShortCodeForm */
/* @var $renderCallback callable */

use kartik\sortable\Sortable;
use common\helpers\Html;
use common\models\ShortCode;

$shortCode = $shortCodeForm->getShortCode();
$shortCodeId = $shortCode->id;
$shortCodeType = $shortCode->type;
$_shortCodeLabel = ($t = $shortCode->label) ? Html::encode($t) : null;
$_shortCodeHint = ($t = $shortCode->hint) ? Html::encode($t) : null;
$_shortCodePlaceholder = ($t = $shortCode->placeholder) ? Html::encode($t) : null;

if (is_callable($renderCallback) && ($r = $renderCallback($shortCodeForm))) {
    echo $r;
    return;
}

$request = Yii::$app->getRequest();
?>

<?= $form->notifies($shortCodeForm) ?>

<?php if ($shortCodeType == ShortCode::TYPE_INPUT): ?>

    <?= $form->field($shortCodeForm, 'content')
        ->textInput([
            'maxlength' => true,
            'placeholder' => $_shortCodePlaceholder,
        ])
        ->label($_shortCodeLabel)
        ->hint($_shortCodeHint) ?>

<?php elseif ($shortCodeType == ShortCode::TYPE_BOOLEAN): ?>

    <?= $form->field($shortCodeForm, 'content')
        ->checkbox()
        ->label($_shortCodeLabel)
        ->hint($_shortCodeHint) ?>

<?php elseif ($shortCodeType == ShortCode::TYPE_REDACTOR): ?>

    <?= $form->field($shortCodeForm, 'content')
        ->textarea([
            'maxlength' => true,
            'rows' => 20,
            'id' => ($t = 'shortCode-' . $shortCodeId),
            'placeholder' => $_shortCodePlaceholder,
        ])
        ->label($_shortCodeLabel)
        ->hint($_shortCodeHint)
        ->widget('backend\widgets\ImperaviRedactorWidget', [
            'fieldId' => $t,
            'modelId' => $shortCodeId,
        ]) ?>

<?php elseif ($shortCodeType == ShortCode::TYPE_GALLERY): ?>

    <div class="shortCode-gallery-blk">
        <?php $filesContent = ''; ?>
        <?php if (($files = $shortCode->sortFiles)): ?>
            <?php ob_start(); ?>
            <div class="shortCode-gallery">
                <?php $items = [] ?>
                <?php foreach ($files as $file): ?>
                    <?php ob_start() ?>
                    <a href="<?= $file->fullUrl ?>"
                       target="_blank"><img
                                src="<?= $file->fullUrl ?>"
                                alt=""
                                class="img-responsive"></a>

                    <?= $form->button(
                        $shortCodeForm,
                        'удалить',
                        'removeImage',
                        $file->id,
                        [
                            'class' => 'btn btn-danger w-xs btn-xs btn-bordered waves-effect waves-light m-t-10',
                            'data-confirm' => 'Вы действительно хотите удалить текущий файл?',
                        ]
                    ) ?>

                    <?= $form->field($shortCodeForm, sprintf('sort[%s]', $file->id),
                        [
                            'template' => '{input}',
                            'options' => ['class' => null],
                        ])
                        ->hiddenInput()
                        ->label(false)
                        ->hint(false) ?>
                    <?php $items[] = [
                        'content' => ob_get_clean(),
                    ]; ?>
                <?php endforeach; ?>
                <?= Sortable::widget([
                    'type' => Sortable::TYPE_GRID,
                    'items' => $items,
                ]) ?>
            </div>
            <?php $filesContent = ob_get_clean(); ?>
        <?php endif; ?>

        <?= $form->field($shortCodeForm, 'images[]', ['template' => "{label}\n{$filesContent}\n{input}\n{hint}\n{error}",])
            ->fileInput([
                'multiple' => true,
                'accept' => 'image/*',
            ])
            ->label($_shortCodeLabel)
            ->hint($_shortCodeHint) ?>

        <div class="form-group">
            <?= $form->button(
                $shortCodeForm,
                'Загрузить',
                'uploadBtn',
                1,
                [
                    'class' => 'btn btn-primary w-md btn-bordered waves-effect waves-light',
                ]
            ) ?>
        </div>
    </div>

<?php elseif ($shortCodeType == ShortCode::TYPE_IMAGE): ?>

    <?php $imageContent = '' ?>

    <?php
    /**
     * @var $file \common\models\File
     */
    ?>
    <?php if (($files = $shortCode->files) && ($file = current($files))): ?>
        <?php ob_start(); ?>
    <div>
        <a href="<?= $file->fullUrl ?>"
           target="_blank"><img
                    src="<?= $file->previewFullUrl ?>"
                    alt=""></a>
    </div>

        <?= $form->button(
            $shortCodeForm,
            'удалить',
            'removeImage',
            $file->id,
            [
                'class' => 'btn btn-danger w-xs btn-xs btn-bordered waves-effect waves-light m-t-10',
                'data-confirm' => 'Вы действительно хотите удалить текущий файл?',
            ]
        ) ?>
        <br>
        <br>
        <?php $imageContent = ob_get_clean(); ?>
    <?php endif; ?>

    <?= $form->field($shortCodeForm, 'image', ['template' => "{label}\n{$imageContent}\n{input}\n{hint}\n{error}",])
        ->fileInput([
            'accept' => 'image/*',
        ])
        ->label($_shortCodeLabel)
        ->hint($_shortCodeHint) ?>

    <div class="form-group">
        <?= $form->button(
            $shortCodeForm,
            'Загрузить',
            'uploadBtn',
            1,
            [
                'class' => 'btn btn-primary w-md btn-bordered waves-effect waves-light',
            ]
        ) ?>
    </div>

<?php else: ?>

    <?= $form->field($shortCodeForm, 'content')
        ->textarea([
            'maxlength' => true,
            'rows' => 5,
            'placeholder' => $_shortCodePlaceholder,
        ])
        ->label($_shortCodeLabel)
        ->hint($_shortCodeHint) ?>

<?php endif; ?>
