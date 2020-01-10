<?php

namespace frontend\widgets;

use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use common\widgets\Script;

/**
 * Class PostListView — Список Публикаций
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostListView extends ListView
{
    /**
     * @var string|callable
     */
    public $itemView = '/posts/_post-list-item';

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $config = ArrayHelper::merge([
            'id' => 'post-list',
            'options' => [
                'class' => null,
            ],
            'emptyTextOptions' => [
                'class' => 'col-12 pb-5',
            ],
            'layout' => '<div class="row news-list">{items}</div>{pager}',
            'summary' => false,
            'itemOptions' => [
                'tag' => 'div',
                'class' => 'col-12 col-md-4 mb-3',
// todo: ???
//                'itemscope' => '',
//                'itemtype' => "http://schema.org/ItemList",
            ],
            'pager' => [
                'class' => NextPageLinkPager::class,
                'nextPageLabel' => 'Больше публикаций',
            ],
        ], $config);

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function afterRun($result)
    {
        $this->getJsScripts();

        return parent::afterRun($result);
    }

    /**
     *
     */
    protected function getJsScripts(): void
    {
        return ;
        Script::begin(); ?>

        <script>
            $('body').on('click', '.next-link', function () {
                var $this = $(this);
                var link = $this.attr('href');
                var $parent = $this.closest('#news-list');
                var $nextLink = $parent.find('.next-link');
                $nextLink.text('Загрузка публикаций...');
                $nextLink.addClass('disabled');

                $.get(link, function (data) {
                    if (data.content) {
                        var $newContent = $(data.content);
                        $parent.find('.post-items').append($newContent.find('.post-items>div'));
                        $nextLink.replaceWith($newContent.find('.next-link'))
                    }
                });
                return false;
            });
        </script>

        <?php Script::end();
    }
}
