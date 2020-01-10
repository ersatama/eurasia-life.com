<?php

/* @see \frontend\controllers\PostsController::actionView() */
/* @var $this yii\web\View */
/* @var $postArticle \common\models\PostArticle */
/* @var $isSimSimPost boolean */

use yii\helpers\Url;
use common\helpers\Html;
use common\packages\htmlhead\HtmlHead;

$this->title = $postArticle->name;
$title = Html::encode($postArticle->name);

$htmlHead = new HtmlHead([
//    'description' => html_entity_decode(strip_tags($postArticle->announce)),
    'image' => (($t = $postArticle->mainImageUrl) ? Url::to($t, true) : null),
]);
$htmlHead->openGraph->article(
    $postArticle->soc_title,
    $postArticle->soc_content,
    (($file = $postArticle->socImage) ? $file->fullUrl : null)
);
?>

<?php if (!$postArticle->isPublished() && $isSimSimPost): ?>
    <div class="container">
        <div class="alert alert-warning" role="alert">
            <?php if ($postArticle->visible): ?>
                Данная публикация скрыта от&nbsp;пользователей
                до&nbsp;<?= $postArticle->publish_at ?>, <?= date('H:i', $postArticle->publish_at) ?>.
            <?php else: ?>
                Данная публикация скрыта от&nbsp;пользователей.
                Для того чтобы опубликовать текущую публикацию укажите галку «Показывать на&nbsp;сайте» в&nbsp;админке.
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div class="container">
    <?= $this->render('_breadcrumbs', ['postArticle' => $postArticle]) ?>
</div>

<article class="container">
    <div class="row justify-content-md-center mb-5">
        <div class="col-12 col-md-10 article">

            <h1><?= Html::encode($this->title) ?></h1>

            <div class="mb-4 mb-md-5">
                <date class="article__date">
                    <?= Html::dateString($postArticle->publish_at) ?>,
                    <?= date('H:i', $postArticle->publish_at) ?>
                </date>

                <!--span class="article__views"><?= max(1, $postArticle->views) ?></span -->
            </div>

            <?php if (($thumbUrl = $postArticle->thumbUrl)): ?>
                <img src="<?= $thumbUrl ?>" class="w-100 mb-3" title="<?= $title ?>" alt="<?= $title ?>">
            <?php endif; ?>

            <?php
            $content = $postArticle->content;
            ?>

            <div class="post__text user-content">
                <?= $content ?>
            </div>
        </div>
    </div>
</article>
