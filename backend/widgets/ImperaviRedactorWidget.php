<?php

namespace backend\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use backend\assets\ImperaviRedactorAsset;

/**
 * Class ImperaviRedactorWidget – Виджет для редактора от Imperavi
 *
 * @package backend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ImperaviRedactorWidget extends InputWidget
{
    /**
     * The name of the jQuery plugin to use for this widget.
     */
    const PLUGIN_NAME = 'redactor';

    /**
     * @var string
     */
    public $fieldId;

    /**
     * @var
     */
    public $modelId;

    /**
     * @var array
     */
    public $clientOptions = [];

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

        $request = Yii::$app->getRequest();

        $modelId = ($t = $this->modelId) ? $t : $this->model->id;

        $this->clientOptions = ArrayHelper::merge([
            'lang' => 'ru',
            'minHeight' => '300px',
            'maxHeight' => '750px',
            'toolbarFixedTopOffset' => 70,
            'linkNewTab' => true,
            'linkNofollow' => true,
            'structure' => true,
//            'placeholder' => true,
            'autoparse' => false,
            'shortcuts' => false,
            'imageResizable' => true,
            'imagePosition' => true,
            'buttonsAdd' => [
                'ol',
                'ul',
//                'line', 'redo', 'undo', 'indent', 'outdent', 'sup', 'sub'
            ],
            'buttonsHide' => [
                'deleted',
                'lists',
            ],
//            'buttonsAddAfter' => [
//                'after' => 'deleted',
//                'buttons' => ['underline']
//            ],
            'plugins' => [
//                'alignment',
                'imagemanager',
                'table',
                'video',
//                'fullscreen',
                'filemanager',
//                'fontcolor',
//                'fontsize',
//                'inlinestyle',
                'widget',
//                'codemirror', // @todo: найти
            ],
            'callbacks' => [
                'file' => [
                    'uploadError' => new JsExpression('function (response) { alert(response.message); }'),
                ],
                'image' => [
                    'uploadError' => new JsExpression('function (response) { alert(response.message); }'),
                ],
                'upload' => [
                    'error' => new JsExpression('function (response) { alert(response.message); }'),
                ],
            ],
            'imageUpload' => Url::to(['redactor-image-upload', 'id' => $modelId]),
            'imageData' => [
                $request->csrfParam => $request->csrfToken,
            ],
            'imageManagerJson' => Url::to(['redactor-image-list', 'id' => $modelId]),
            'fileUpload' => Url::to(['redactor-file-upload', 'id' => $modelId]),
            'fileData' => [
                $request->csrfParam => $request->csrfToken,
            ],
            'fileManagerJson' => Url::to(['redactor-file-list', 'id' => $modelId]),
//            'codemirror' => [
//                'lineWrapping' => true,
//                'mode' => 'text/html',
//                'theme' => 'zenburn',
//            ]
        ], $this->clientOptions);

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
        $clientOptions = is_array($this->clientOptions) ? $this->clientOptions : [];

        $view = $this->getView();

        $asset = ImperaviRedactorAsset::register($view);

        if (isset($clientOptions['lang'])) {
            $asset->lang = $clientOptions['lang'];
        }

        if (isset($clientOptions['plugins'])) {
            $asset->plugins = $clientOptions['plugins'];
        }

        if (isset($clientOptions['codemirror'])) {
            $asset->codemirror = $clientOptions['codemirror'];
        }

        $view->registerJs(sprintf('$R("#%s", %s);',
            $this->fieldId,
            Json::htmlEncode($clientOptions)
        ));
    }
}
