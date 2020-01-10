<?php

/* @var $this yii\web\View */
/* @var $title string */
/* @var $pageUrl string */
/* @var $languages common\models\Language[] */
/* @var $shortCodeFormGroups [] */
/* @var $pageOnSiteMap [] */

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\Notify;
use common\widgets\Script;
use backend\models\ShortCodeForm;
use backend\widgets\ShortCodeFields;

$this->title = $title;
?>
<div class="p-20">

    <h1 class="m-b-20"><?= Html::encode($this->title) ?></h1>

    <ul class="nav nav-tabs m-b-20">
        <?php $isFirst = true ?>
        <?php foreach ($languages as $language): ?>
            <li<?= $isFirst ? ' class="active"' : '' ?>>
                <a href="#lang-id-<?= $language->id ?>"
                   data-toggle="tab"
                   aria-expanded="<?= $isFirst ? 'true' : 'false' ?>">
                    <span><?= Html::encode($language->name) ?></span>
                </a>
            </li>
            <?php $isFirst = false ?>
        <?php endforeach; ?>
    </ul>

    <?= Notify::widget(['position' => 'header']) ?>

    <?php if ($shortCodeFormGroups): ?>

        <?php $form = ActiveForm::begin(); ?>

        <div class="tab-content p-0">
            <?php $isFirst = true ?>
            <?php foreach ($languages as $language): ?>
                <?php
                /**
                 * @var $shortCodeForms ShortCodeForm[]
                 */
                $shortCodeForms = $shortCodeFormGroups[$language->id] ?? [];
                ?>
                <div class="tab-pane <?= $isFirst ? ' active' : '' ?>"
                     id="lang-id-<?= Html::encode($language->id) ?>">

                    <?php
                    $shortCodeNameId = null;
                    ?>
                    <?php foreach ($shortCodeForms as $shortCodeForm): ?>

                        <div class="m-b-30 p-t-10" id="<?= $shortCodeForm->formName() ?>-field-blk">
                            <?= ShortCodeFields::widget([
                                'form' => $form,
                                'shortCodeForm' => $shortCodeForm,
                                'renderCallback' => function (ShortCodeForm $shortCodeForm) use ($form, &$shortCodeNameId) {
                                    $shortCode = $shortCodeForm->shortCode;

                                    if ($shortCode->short_code == 'name') {
                                        $shortCodeNameId = Html::getInputId($shortCodeForm, 'content');
                                    }

                                    if ($shortCode->short_code == 'slug') {
                                        $_shortCodeLabel = ($t = $shortCode->label) ? Html::encode($t) : null;
                                        $_shortCodeHint = ($t = $shortCode->hint) ? Html::encode($t) : null;
                                        $_shortCodePlaceholder = ($t = $shortCode->placeholder) ? Html::encode($t) : null;

                                        return $form->field($shortCodeForm, 'content')
                                            ->textInput([
                                                'maxlength' => true,
                                                'placeholder' => $_shortCodePlaceholder,
                                            ])
                                            ->label($_shortCodeLabel)
                                            ->hint($_shortCodeHint)
                                            ->widget('backend\widgets\SlugField', ['targetSelector' => '#' . $shortCodeNameId]);
                                    }
                                }
                            ]) ?>
                        </div>

                    <?php endforeach; ?>

                </div>
                <?php $isFirst = false ?>
            <?php endforeach; ?>
        </div>

        <div class="form-group fixed-form-btn-blk">
            <?= Html::submitButton(
                'Сохранить',
                [
                    'name' => ShortCodeForm::BUTTON_MAIN_SAVE,
                    'class' => 'btn btn-success btn-lg btn-block w-md btn-bordered waves-effect waves-light',
                ]
            ) ?>

            <?php if (isset($pageUrl) && $pageUrl): ?>
                <div class="link-blk">
                    <a href="<?= $pageUrl ?>" target="_blank">Страница на&nbsp;сайте</a>
                </div>
            <?php endif; ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php else: ?>
        <div class="card-box">
            Редактируемые поля не найдены.
        </div>
    <?php endif; ?>
</div>

<?php Script::begin() ?>

<script>
    var map = <?= json_encode($pageOnSiteMap) ?>;

    $.each(map, function (index, value) {
        map['#lang-id-' + index] = value;
        delete map[index];
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var link = map[$(e.target).attr("href")];
        if (typeof (link) !== 'undefined') {
            $('.link-blk a').attr('href', link);
        }
    });
</script>

<?php Script::end() ?>
