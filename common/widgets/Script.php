<?php

namespace common\widgets;

use yii\base\Exception;
use yii\base\Widget;
use yii\web\JqueryAsset;
use yii\web\View;

/**
 * Class Script – регистрация js-скрипта
 * @package common\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Script extends Widget
{
    /**
     * @see \yii\web\View:POS_*
     * @var int
     */
    public $position = View::POS_READY;

    /**
     * @var mixed
     */
    public $key;

    /**
     * @var string – JS-контент
     */
    public $content = '';

    /**
     * @var bool - удалять тэг <script>
     */
    public $removeScriptTag = true;

    /**
     * @var null|array - зависимости
     */
    public $depends;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        ob_start();
    }

    /**
     * @inheritdoc
     * @return string|void
     * @throws Exception
     */
    public function run()
    {
        $this->registerDepends();

        $js = (string)$this->content;
        $js .= ob_get_clean();

        // remove script tag
        if ($this->removeScriptTag) {
            $js = preg_replace('/<\/?script[^>]*?>/uis', '', $js);
        }

        if ($js) {
            $this->view->registerJs($js, $this->position, $this->key);
        }
    }

    /**
     * Регистрация зависимостей
     * @throws Exception
     */
    protected function registerDepends()
    {
        if (!$this->depends) {
            return;
        }

        $view = $this->view;

        $depends = is_array($this->depends) ? $this->depends : [$this->depends];
        foreach ($depends as $depend) {

            if (is_array($depend)) {
                $ext = pathinfo($depend[0], PATHINFO_EXTENSION);
            } else {
                $ext = pathinfo($depend, PATHINFO_EXTENSION);

                $depend = [$depend];

                if ($ext == 'js') {
                    $depend[1] = [
                        'position' => View::POS_END,
                        'depends' => [JqueryAsset::class]
                    ];
                }
            }

            if (!isset($depend[1])) {
                $depend[1] = [];
            }

            if (!isset($depend[2])) {
                $depend[2] = null;
            }

            if ($ext == 'js') {
                $view->registerJsFile($depend[0], $depend[1], $depend[2]);
            } else if ($ext == 'css') {
                $view->registerCssFile($depend[0], $depend[1], $depend[2]);
            } else if (class_exists($depend[0])) {
                $depend[0]::register($view);
            } else {
                throw new Exception(sprintf('Unknown extension `%s`', $ext));
            }
        }
    }
}
