<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use common\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>

<div id="wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <a
                href="<?= Url::home() ?>"
                class="logo"><span><span>Guardian&nbsp;5</span></span><i class="mdi mdi-layers"></i></a>
        </div>
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <button class="button-menu-mobile open-left waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a
                            href="<?= Url::to('@frontendWeb') ?>"
                            class="menu-item"
                            target="_blank">Перейти на сайт <i class="mdi mdi-open-in-new"></i></a>
                    </li>
                    <li class="dropdown user-box">
                        <a
                            href=""
                            class="dropdown-toggle waves-effect right-bar-toggle right-menu-item"
                            data-toggle="dropdown"
                            aria-expanded="true"><i class="mdi mdi-account"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                            <li>
                                <a
                                    href="<?= Url::to(['/site/settings']); ?>"><i
                                        class="ti-settings m-r-5"></i>Настройки</a>
                            </li>
                            <li>
                                <?= Html::beginForm(['/site/logout'], 'post', ['id' => 'layout-exit-form'])
                                . Html::submitButton('<i class="ti-power-off m-r-5"></i> Выход', ['class' => 'btn btn-link'])
                                . Html::endForm() ?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?= $this->render('_main-nav') ?>
    <div class="content-page">
        <div class="content">
            <div class="container">
                <?= $content ?>
            </div>
        </div>
        <footer class="footer text-right">
            <?= ($sY = 2017) . (($y = date('Y')) > $sY ? ' — ' . $y : '') ?>
            ©&nbsp;<a href="http://grafica.kz" target="_blank">Grafica</a>.
        </footer>
    </div>
</div>

<script>var resizefunc = [];</script>

<?php $this->endContent(); ?>
