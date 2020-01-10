<?php

namespace backend\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Json;
use backend\assets\TimepickerAsset;

/**
 * Class TimepickerWidget
 *
 * @package backend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class TimepickerWidget extends InputWidget
{
    /**
     * The name of the jQuery plugin to use for this widget.
     */
    const PLUGIN_NAME = 'timepicker';

    /**
     * @var string
     */
    public $fieldId;

    /**
     * @var array
     */
    public $pluginOptions;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $return = parent::init();

        if (!$this->fieldId) {
            $this->fieldId = isset($this->options['id']) ? $this->options['id'] : $this->getId();
        }

        return $return;
    }

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
        $pluginOptions = is_array($this->pluginOptions) ? $this->pluginOptions : [];

        $view = $this->getView();

        TimepickerAsset::register($view);

        $view->registerJs(sprintf('jQuery("#%s").%s(%s);',
            $this->fieldId,
            static::PLUGIN_NAME,
            Json::htmlEncode($pluginOptions)
        ));
    }
}
