<?php

namespace backend\widgets;

use yii\helpers\Url;
use yii\widgets\InputWidget;
use common\widgets\Script;

/**
 * Class SlugField – Ссылка "Сгенерировать" для slug (URL) поля
 *
 * @package backend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class SlugField extends InputWidget
{
    /**
     * @var null|string
     */
    public $targetSelector;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        return $this->field->parts['{input}'];
    }

    /**
     * Registers the needed JavaScript.
     */
    protected function registerClientScript()
    {
        $fieldId = isset($this->options['id']) ? $this->options['id'] : $this->getId();

        Script::begin(); ?>
        <script>
            (function () {
                var $inputSlug = $('#<?= $fieldId ?>'), $inputName;

                <?php if($this->targetSelector): ?>
                $inputName = $('<?= $this->targetSelector?>');
                <?php else: ?>
                $inputName = $inputSlug
                    .closest('form')
                    .find('input[name=' + $inputSlug.attr('name').split('[')[0] + '\\[name\\]]');
                <?php endif; ?>

                // link
                $('<a>', {
                    text: 'Сгенерировать',
                    href: '#',
                    class: 'get-slug',
                    click: function () {
                        var string = $inputName.val();
                        if (!string)
                            return false;

                        $inputSlug.attr({disabled: true});
                        $.get('<?= Url::to(['site/get-slug']); ?>', {string: string}, function (data) {
                            $inputSlug.val(data.slug).attr({disabled: false});
                        });
                        return false;
                    }
                }).insertAfter($inputSlug);
            }());
        </script>
        <?php Script::end();
    }
}
