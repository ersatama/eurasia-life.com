<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $loginForm backend\models\LoginForm */

use yii\bootstrap\ActiveForm;
use common\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);

$this->params['html-class'] = 'account-pages-bg';
$this->params['body-class'] = 'bg-transparent';

$this->title = 'Вход в админку';
?>
<div class="site-login container-alt">
    <div class="row">
        <div class="col-sm-12">
            <div class="wrapper-page">
                <div class="m-t-40 account-pages">
                    <h1 class="text-center account-logo-box logo">
                        <span><span>Guardian&nbsp;5</span></span>
                    </h1>
                    <div class="account-content">

                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'site-login_form',
                            'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "<div class=\"col-xs-12\">{input}</div>{error}",
                                'errorOptions' => ['class' => 'help-block col-xs-12'],
                            ],
                        ]);
                        ?>

                        <?= $form
                            ->field($loginForm, 'email')
                            ->input('email', ['autofocus' => true, 'required' => 'required', 'placeholder' => 'Эл. почта']) ?>

                        <?= $form
                            ->field($loginForm, 'password')
                            ->passwordInput(['required' => 'required', 'placeholder' => 'Пароль']) ?>

                        <div class="form-group account-btn text-center m-t-10">
                            <div class="col-xs-12">
                                <?= Html::submitButton('Войти', ['class' => 'btn w-md btn-bordered btn-danger waves-effect waves-light']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
