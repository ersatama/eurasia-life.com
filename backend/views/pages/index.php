<?php

/* @var $this yii\web\View */
/* @var $pages common\models\Page[] */

use yii\helpers\Url;
use common\helpers\Html;
use common\widgets\Notify;
use backend\widgets\Move;

$this->title = 'Контент-страницы';
?>
<div class="p-20">
    <h1 class="m-b-20"><?= Html::encode($this->title) ?></h1>
    <?= Notify::widget() ?>
    <p><?= Html::a('Добавить новую страницу', ['create'], ['class' => 'text-success']) ?></p>
    <?php if ($pages): ?>
        <div class="custom-dd-empty dd" id="page-list" data-url="<?= Url::to(['move']) ?>">
            <?php
            $ulOpen = '<ol class="dd-list">';
            $ulClose = '</ol>';
            $liOpen = '<li class="dd-item dd3-item" data-id="%s">';
            $liClose = '</li>';

            $level = 0;
            foreach ($pages as $page) {
                if ($page->depth == $level) {
                    echo $liClose;
                } else if ($page->depth > $level) {
                    echo $ulOpen;
                } else {
                    echo $liClose;
                    for ($i = $level - $page->depth; $i; $i--) {
                        echo $ulClose . $liClose;
                    }
                }

                echo sprintf($liOpen, $page->id);

                echo '<div class="dd-handle dd3-handle"></div>';
                echo '<div class="dd3-content">';
                echo Html::a(
                    Html::encode($page->name),
                    ['update', 'id' => $page->id],
                    ['class' => ($page->visible ? null : 'text-muted')]
                );
                echo '</div>';

                $level = $page->depth;
            }

            for ($i = $level; $i; $i--) {
                echo $liClose . $ulClose;
            }
            ?>
        </div>
    <?php else: ?>
        <p>Страницы не найдены.</p>
    <?php endif; ?>
</div>

<?php Move::widget(['selector' => '#page-list', 'maxDepth' => 3]) ?>
