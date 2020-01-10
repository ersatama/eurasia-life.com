<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class ImperaviRedactorAsset – Подключаем редактора от Imperavi
 * @package backend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ImperaviRedactorAsset extends AssetBundle
{
    public $basePath = '@webroot/imperavi-redactor-3';

    public $baseUrl = '@web/imperavi-redactor-3';

    // @todo: npm?
    public $css = [
        'redactor.min.css',
//        '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/codemirror.min.css',
    ];

    public $js = [
        'redactor.min.js',
//        '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/codemirror.min.js',
//        '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/mode/xml/xml.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @var string - язык
     */
    public $lang = 'ru';

    /**
     * @var array - плагины
     */
    public $plugins = [];

    /**
     * @var array - опции codemirror
     */
    public $codemirror = [];

    /**
     * @var array - карта для плагинов
     */
    public $pluginsMap = [];

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        $this->registerLangFiles();

        $this->registerPluginFiles();

        if (isset($this->codemirror['theme'])) {
//            $this->css[] = sprintf('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/theme/%s.min.css', $this->codemirror['theme']);
        }

        return parent::registerAssetFiles($view);
    }

    /**
     * Регистрируем файлы для языков
     * @return $this
     */
    protected function registerLangFiles()
    {
        if (($lang = $this->lang)) {
            $this->js[] = '_langs/' . $lang . '.js';
        }

        return $this;
    }

    /**
     * Регистриуем файлы плагинов
     * @return $this
     */
    protected function registerPluginFiles()
    {
        if (($plugins = $this->plugins)) {
            foreach ($plugins as $plugin) {
                if (isset($this->pluginsMap[$plugin])) {
                    if (isset($this->pluginsMap[$plugin]['js'])) {
                        $this->js[] = $this->pluginsMap[$plugin]['js'];
                    }
                    if (isset($this->pluginsMap[$plugin]['css'])) {
                        $this->css[] = $this->pluginsMap[$plugin]['css'];
                    }
                } else {
                    $this->js[] = '_plugins/' . $plugin . '/' . $plugin . '.min.js';
                }
            }
        }

        return $this;
    }
}