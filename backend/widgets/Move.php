<?php

namespace backend\widgets;

use yii\base\Exception;
use yii\base\Widget;
use common\widgets\Script;

/**
 * Class Move — Виджет перемещения моделей по дереву
 * @package backend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Move extends Widget
{
    /**
     * @var string
     */
    public $selector;

    /**
     * @var int
     */
    public $maxDepth = 1;

    /**
     * @inheritdoc
     * @return string|void
     * @throws Exception
     */
    public function run()
    {
        Script::begin([
            'depends' => '/zircos/plugins/nestable/jquery.nestable.js',
        ]);
        ?>
        <script>
            var $list = $('<?= $this->selector ?>');
            var itemClass = 'dd-item';
            var $dragHandle, changeEvent, prevItemId;

            $list.nestable({
                maxDepth: <?= max(1, (int)$this->maxDepth) ?>,
            }).on('mousedown', function (e) {
                $dragHandle = $(e.target);
                prevItemId = $list.find('.dd-placeholder').prev('.' + itemClass).data('id');
            }).on('change', function () {
                changeEvent = true;
            });
            $(window).on('mouseup', function (e) {
                setTimeout(function () {
                    if (!$dragHandle || !changeEvent) {
                        return;
                    }

                    var $item = $dragHandle.closest('.' + itemClass);
                    var data = {
                        id: $item.data('id'),
                        afterId: $item.prev('.' + itemClass).data('id'),
                        parentId: $item.parent().closest('.' + itemClass).data('id'),
                    };

                    if (data.afterId !== prevItemId) {
                        $.post($list.data('url'), data, function (data) {
                            console.log(data);
                        });
                    }

                    $dragHandle = changeEvent = prevItemId = null;
                }, 1);
            });
        </script>
        <?php Script::end();
    }
}



