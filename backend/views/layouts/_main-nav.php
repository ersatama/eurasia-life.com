<?php

/* @var $this \yii\web\View */

use yii\helpers\Url;
use common\helpers\Html;
use common\models\LandingPage;
use common\models\Language;

$currentPath = Yii::$app->request->pathInfo;

$fActive = function ($path) use ($currentPath) {
    return $path == $currentPath ? ' class="active"' : '';
};

$fMenu = function ($label, $url, $icon = null, $kids = null) {
    return [
        'label' => $label,
        'url' => $url,
        'icon' => $icon,
        'kids' => $kids,
    ];
};

$menu = [];
$menu[] = $fMenu('Главная', Url::to(['/frontend/main']), 'mdi mdi-crown');

if (isset($this->params['landing-pages'])) {
    $menu[] = $fMenu(
        'Посадочные',
        '#',
        'mdi mdi-airplane-landing',
        array_map(
            function (LandingPage $landingPage) use ($fMenu) {
                return $fMenu($landingPage->name, Url::to(['/frontend/landing-page', 'id' => $landingPage->id]));
            },
            LandingPage::filterByLangId($this->params['landing-pages'], Language::ID_RU)
        )
    );
}

$menu[] = $fMenu('Новости', Url::to(['/posts']), 'glyphicon glyphicon-file');
$menu[] = $fMenu('Контент-страницы', Url::to(['/pages']), 'mdi mdi-format-text');

$supportEmail = isset(Yii::$app->params['backendSupportEmail']) ? Yii::$app->params['backendSupportEmail'] : '';
?>
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <?php foreach ($menu as $_menu): ?>
                    <?php if (isset($_menu['kids'])): ?>
                        <li class="has_sub">
                            <a href="<?= $_menu['url'] ?>" class="waves-effect">
                                <i class="<?= $_menu['icon'] ?>"></i>
                                <span><?= $_menu['label'] ?></span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <?php foreach ($_menu['kids'] as $__menu): ?>
                                    <li>
                                        <a href="<?= $__menu['url'] ?>"><?= $__menu['label'] ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= $_menu['url'] ?>" class="waves-effect">
                                <i class="<?= $_menu['icon'] ?>"></i>
                                <span><?= $_menu['label'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="clearfix"></div>
        <?php if ($supportEmail): ?>
            <div class="help-box">
                <h5 class="text-muted m-t-0">Нужна помощь?</h5>
                <p>
                    <span class="text-custom">Эл. почта:</span><br/>
                    <a href="mailto:<?= $supportEmail ?>"><?= $supportEmail ?></a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
