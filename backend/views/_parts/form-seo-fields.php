<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $model yii\base\Model */

use common\helpers\Html;

$modelClassName = get_class($model);
?>
<div class="panel panel-default panel-border">
    <div class="panel-heading">
        <h3 class="panel-title">В соц. сетях</h3>
        <p class="panel-sub-title font-13 text-muted">Превью страницы в соц сетях</p>
    </div>
    <div class="panel-body">
        <?= $form->field($model, 'soc_title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'soc_content')->textarea(['maxlength' => true]) ?>

        <div class="upload-image">
            <?php ob_start(); ?>
            <?php if (($image = $model->socImage) && ($imageUrl = $image->fullUrl)): ?>
                <div class="upload-image__preview-blk m-b-10">
                    <a href="<?= $imageUrl ?>" target="_blank" class="upload-image__link">
                        <img src="<?= $image->previewFullUrl ?>" alt="" class="upload-image__image">
                    </a>
                    <button type="submit"
                            name="<?= Html::getInputName($model, 'btn_remove_image') ?>"
                            value="soc"
                            class="btn btn-danger btn-xs m-t-10"
                            data-confirm="Вы действительно хотите удалить картинку?">Удалить
                    </button>
                </div>
            <?php endif; ?>
            <?php $buf = ob_get_clean(); ?>
            <?= $form
                ->field($model, 'soc_image', [
                    'template' => "{label}\n{$buf}\n{input}\n{hint}\n{error}",
                ])
                ->fileInput([
                    'accept' => implode(',', $modelClassName::SOC_IMAGE_TYPES),
                    'class' => 'filestyle',
                    'data-buttonText' => 'Выбрать' . (trim($buf) ? ' новую' : '') . ' картинку',
                    'data-input' => 'false',
                ]) ?>
        </div>
    </div>
</div>
