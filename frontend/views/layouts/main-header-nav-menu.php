<?php

/* @var $this \yii\web\View */

use yii\helpers\Url;
use common\helpers\Html;
use common\models\LandingPage;
use frontend\widgets\MainMenu;

if (!isset($this->params['landing-pages']) || !$this->params['landing-pages']) {
    return '';
}

/**
 * @var $landingPages common\models\LandingPage[]
 */
$landingPages = LandingPage::filterByLangSlug($this->params['landing-pages'], Yii::$app->language);

$currentUrl = Yii::$app->request->url;

$menuItems = [];

// Landing Pages
foreach ($landingPages as $landingPage) {
    $menuItems[] = [
        'label' => $landingPage->name,
        'url' => ['landing-pages/view', $landingPage],
    ];
}

// Pages
if (isset($this->params['pages-main']) && $this->params['pages-main']) {
    /**
     * @var $pages \common\models\Page
     */
    $pages = $this->params['pages-main'];

    foreach ($pages as $page) {
        $menuItems[] = [
            'label' => Html::encode($page->name),
            'url' => $page->url,
            'options' => [
                'class' => 'main-nav__item main-nav__item_light'
            ],
        ];
    }
}

$menuItems[] = [
    'label' => Yii::t('app', 'Новости'),
    'url' => ['posts/index'],
    'options' => [
        'class' => 'main-nav__item main-nav__item_light'
    ],
];

// active item
foreach ($menuItems as &$menuItem) {
    $url = Url::to($menuItem['url']);
    $pattern = sprintf('/^%s(?:\/|$)/uis', preg_quote($url, '/'));
    $menuItem['active'] = preg_match($pattern, $currentUrl);
}

?>

<?= MainMenu::widget(['items' => $menuItems]) ?>
