<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception \yii\web\HttpException */

use yii\helpers\Url;
use common\helpers\Html;
use common\widgets\Script;
use backend\assets\AppAsset;
use backend\assets\ZircosAsset;

AppAsset::register($this);

$zircosAsset = ZircosAsset::register($this);

$this->params['html-class'] = 'account-pages-bg';
$this->params['body-class'] = 'bg-transparent';

$this->title = $name;
?>
<div class="site-error container-alt">
    <div class="row">
        <div class="col-sm-12 text-center">
            <div class="wrapper-page">

                <?php if ($exception->statusCode == 404): ?>

                    <img src="<?= $zircosAsset->baseUrl ?>/images/animat-search-color.gif" alt="" height="120">
                    <h2 class="text-uppercase text-danger"><?= nl2br(Html::encode($message)) ?></h2>

                <?php elseif ($exception->statusCode == 500): ?>

                    <img src="<?= $zircosAsset->baseUrl ?>/images/animat-customize-color.gif" alt="" height="120">
                    <h1><?= $exception->statusCode ?></h1>
                    <h3 class="text-uppercase text-danger">Внутренняя ошибка сервера</h3>

                <?php else: ?>

                    <img src="<?= $zircosAsset->baseUrl ?>/images/animat-customize-color.gif" alt="" height="120">
                    <h1><?= $exception->statusCode ?></h1>
                    <h3 class="text-uppercase text-danger"><?= nl2br(Html::encode($this->title)) ?></h3>
                    <p class="text-muted">
                        <?= nl2br(Html::encode($message)) ?>
                    </p>

                <?php endif; ?>

                <a href="<?= Url::home() ?>" id="error-back-link" class="btn btn-success waves-effect waves-light m-t-20">Вернуться в&nbsp;админку</a>
            </div>
        </div>
    </div>
</div>

<?php Script::begin(); ?>
    <script>
        $('#error-back-link').click(function () {
            if (history.length === 1) {
                return true;
            }
            history.back();
            return false;
        });
    </script>
<?php Script::end(); ?>