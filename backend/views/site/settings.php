<?php

/* @var $this yii\web\View */
/* @var $passwordForm backend\models\PasswordForm */

use yii\bootstrap\ActiveForm;
use common\helpers\Html;

$this->title = 'Настройки';
?>
<div class="site-settings">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-xs-12 col-md-8 col-lg-6">
            <div class="card-box">

                <h2>Сменить пароль</h2>
                <?php
                $form = ActiveForm::begin([
                    'id' => 'change-password-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "<div class=\"col-xs-12\">{input}</div>{error}",
                        'errorOptions' => ['class' => 'help-block col-xs-12'],
                    ],
                ]);
                ?>

                <?= $form
                    ->field($passwordForm, 'password')
                    ->passwordInput(['autofocus' => true, 'required' => 'required', 'placeholder' => 'Старый пароль']) ?>

                <?= $form
                    ->field($passwordForm, 'new_password')
                    ->passwordInput(['required' => 'required', 'placeholder' => 'Новый пароль']) ?>

                <div class="form-group">
                    <div class="col-xs-12">
                        <?= Html::submitButton('Сменить пароль', ['class' => 'btn w-md btn-bordered btn-primary waves-effect waves-light']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>