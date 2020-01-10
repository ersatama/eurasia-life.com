<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $appAssetBaseUrl string */

use common\helpers\Html;

if (!isset($this->params['pages-main']) || !$this->params['pages-main']) {
    return;
}

/**
 * @var $pages \common\models\Page
 */
$pages = $this->params['pages-main'];

?>

<?php foreach ($pages as $page): ?>
    <li class="list-inline-item my-2">
        <?= Html::a(Html::encode($page->name), $page->url) ?>
    </li>
<?php endforeach; ?>
