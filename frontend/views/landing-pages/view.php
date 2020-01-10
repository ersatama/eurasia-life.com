<?php

/**
 * @var $this yii\web\View
 * @var $landingPage common\models\LandingPage
 * @var $requestForm frontend\models\RequestForm
 */

use yii\helpers\Url;
use common\helpers\Html;

?>

<?php if (!$landingPage->isVisible()): ?>
    <?php
    $url = Yii::getAlias('@backendWeb') . Url::to(['frontend/landing-page', 'id' => $landingPage->id]);
    ?>
    <div class="container">
        <div class="alert alert-warning">
            Данная страница скрыта от&nbsp;пользователей.
            Для того чтобы опубликовать текущую страницу укажите галку «Показывать на&nbsp;сайте»
            в&nbsp;<?= Html::a('админке', $url, ['target' => '_blank']) ?>.
        </div>
    </div>
<?php endif; ?>

<?php if ($landingPage->isStrahovanieRabotnikovOtNeschastnyhSluchaev()): ?>
    <?= $this->render('view-strahovanie-rabotnikov-ot-neschastnyh-sluchaev', [
        'landingPage' => $landingPage,
        'requestForm' => $requestForm,
    ]) ?>
<?php elseif ($landingPage->isAnnuitetnoeStrahovanieVRamkahOsns()): ?>
    <?= $this->render('view-annuitetnoe-strahovanie', [
        'landingPage' => $landingPage,
        'requestForm' => $requestForm,
    ]) ?>
<?php elseif ($landingPage->isStrahovanieZhizniZayomshhikovPoKreditam()): ?>
    <?= $this->render('view-strahovanie-zhizni', [
        'landingPage' => $landingPage,
        'requestForm' => $requestForm,
    ]) ?>
<?php elseif ($landingPage->isPensionnyjAnnuitet()): ?>
    <?= $this->render('view-pensionnyj-annuitet', [
        'landingPage' => $landingPage,
        'requestForm' => $requestForm,
    ]) ?>
<?php endif; ?>

<div class="container">
<?= $this->render('_breadcrumbs', [
    'landingPage' => $landingPage
]) ?>
</div>
