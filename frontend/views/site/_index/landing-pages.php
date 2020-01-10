<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use common\helpers\Html;
use common\models\LandingPage;

if (!isset($this->params['landing-pages']) || !$this->params['landing-pages']) {
    return '';
}

/**
 * @var $landingPages common\models\LandingPage[]
 */
$landingPages = LandingPage::filterByLangSlug($this->params['landing-pages'], Yii::$app->language);

// todo: !!!
$imageMap = [
    1 => '/static/001.jpg',
    '/static/002.jpg',
    '/static/003.jpg',
    '/static/004.jpg',

    '/static/001.jpg',
    '/static/002.jpg',
    '/static/003.jpg',
    '/static/004.jpg',

    '/static/001.jpg',
    '/static/002.jpg',
    '/static/003.jpg',
    '/static/004.jpg',
];

?>

<section class="container">
    <div class="row landing-page-list">
        <?php $cssBuf = '' ?>
        <?php foreach ($landingPages as $landingPage): ?>
            <?php
            $landingPageId = $landingPage->id;
            $landingPageName = Html::encode($landingPage->name);
            $imageUrl = $imageMap[$landingPageId] ?? '';
            $landingPageClassName = sprintf('landing-page-list__link_%d', $landingPageId);
            if ($imageUrl) {
                $cssBuf .= sprintf(
                    '.%s { background-image: url("%s") }',
                    $landingPageClassName,
                    $imageUrl
                );
            }
            ?>
            <div class="col-12 col-md-6 landing-page__item mx-auto">
                <a href="<?= Url::to(['landing-pages/view', $landingPage]) ?>"
                   class="landing-page-list__link <?= $landingPageClassName ?>">
                    <span class="landing-page-list__title"><?= $landingPageName ?></span>
                </a>
            </div>
        <?php endforeach; ?>
        <?php $this->registerCss($cssBuf) ?>
    </div>
</section>
