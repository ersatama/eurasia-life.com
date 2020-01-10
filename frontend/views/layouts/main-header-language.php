<?php

/* @var $this \yii\web\View */

use yii\helpers\Url;
use common\helpers\Html;

$map = [];

if (isset($this->params['languages']) && ($languages = $this->params['languages'])) {
    /**
     * @var $languages \common\models\Language[]
     */
    foreach ($languages as $language) {
        $map[$language->id] = [
            'label' => $language->slug,
            'url' => Url::current(['language' => $language->slug]),
            'active' => Yii::$app->language == $language->slug,
        ];
    }
}

if (isset($this->params['languages-urls']) && ($languagesUrls = $this->params['languages-urls'])) {
    foreach ($map as $key => &$value) {
        if (isset($languagesUrls[$key])) {
            $value = array_merge($value, $languagesUrls[$key]);
        }
    }
}

if (!$map) {
    return '';
}

?>

<ul class="lang-nav list-inline d-inline-block">
    <?php foreach ($map as $data): ?>
        <li class="list-inline-item<?= $data['active'] ? ' active' : '' ?>">
            <?php if ($data['active']): ?>
                <?= $data['label'] ?>
            <?php else: ?>
                <?= Html::a($data['label'], $data['url']); ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
