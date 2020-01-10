<?php

/**
 * @var $this yii\web\View
 * @var $page common\models\Page
 */

use yii\helpers\Url;
use common\helpers\Html;
use common\packages\htmlhead\HtmlHead;

$this->title = $page->titleOrName;
$htmlHead = new HtmlHead();
$htmlHead->openGraph->changeTypeToArticle();
?>
<section class="text-page">
    <div class="container text-page__content pb-5">
        <div class="row">
            <div class="col-12">
                <?php if (!$page->visible): ?>
                    <?php
                    $url = Yii::getAlias('@backendWeb') . Url::to(['pages/update', 'id' => $page->id]);
                    ?>
                    <div class="alert alert-warning">
                        Данная публикация скрыта от&nbsp;пользователей.
                        Для того чтобы опубликовать текущую публикацию укажите галку «Показывать на&nbsp;сайте»
                        в&nbsp;<?= Html::a('админке', $url, ['target' => '_blank']) ?>.
                    </div>
                <?php endif; ?>

                <?= $this->render('_breadcrumbs', [
                    'page' => $page,
                ]) ?>

                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-12 col-md-8 user-content">
                <?php /*/ HtmlPurifier::process() /*/ ?>
                <?= $page->body ?>
            </div>
        </div>
    </div>
</section>
