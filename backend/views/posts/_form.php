<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $languages common\models\Language[] */
/* @var $postArticleForm backend\models\PostArticleForm */

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\Notify;
use backend\models\PostArticleForm;

$postArticleId = $postArticleForm->getPostArticle()->id;
$isCreateForm = $postArticleForm->scenario === PostArticleForm::SCENARIO_CREATE;

?>

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

<?= Notify::widget() ?>

<?php $form = ActiveForm::begin([
    'scrollToErrorOffset' => 100,
]); ?>

<?= $form->notifies($postArticleForm) ?>

<div class="tab-content p-0">
    <?php $isFirst = true ?>
    <?php foreach ($languages as $language): ?>
        <?php
        $_postArticleForm = $language->id == $postArticleForm->postArticle->lang_id
            ? $postArticleForm
            : $postArticleForm->getLangFormByLangId($language->id);
        if (!$_postArticleForm) {
            continue;
        }
        ?>
        <div class="tab-pane <?= $isFirst ? ' active' : '' ?>" id="lang-id-<?= Html::encode($language->id) ?>">
            <div class="m-b-30 p-t-10" id="<?= $postArticleForm->formName() ?>-field-blk">
                <?= $this->render('_form-elements', [
                    'form' => $form,
                    'postArticleForm' => $_postArticleForm,
                ]) ?>
            </div>
        </div>
        <?php $isFirst = false ?>
    <?php endforeach; ?>
</div>

<div class="form-group fixed-form-btn-blk">
    <?= Html::submitButton(
        $isCreateForm ? 'Добавить' : 'Сохранить',
        ['class' => 'btn btn-success btn-lg btn-block w-md btn-bordered waves-effect waves-light']
    ) ?>

    <?php if (!$isCreateForm): ?>
        <div class="link-blk">
            <a href="<?= $postArticleForm->getPostArticle()->fullUrlSimSim ?>"
               target="_blank">Страница на&nbsp;сайте</a>
        </div>
    <?php endif; ?>
</div>

<?php ActiveForm::end(); ?>

