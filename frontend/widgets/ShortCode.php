<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\assets\AppAsset;
use common\models\ShortCode as ShortCodeModel;

/**
 * Class ShortCode – Виджет помогает показывать шорткоды на сайте
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCode extends Widget
{
    // под этим ключем храним шорткоды в параметрах объекта view
    CONST VIEW_PARAMS_KEY = 'short-codes';

    /**
     * @inheritdoc
     */
    public static function begin($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        return parent::begin($config);
    }

    /**
     * @inheritdoc
     */
    public static function end()
    {
        $buf = ob_get_clean();

        ob_start();
        $return = parent::end();
        $result = ob_get_clean();

        if ($result) {
            printf($buf, $result);
        }

        return $return;
    }

    /**
     * @param string $shortCode
     * @param array $config
     * @return static
     */
    public static function beginShortCode($shortCode, $config = [])
    {
        $config['shortCode'] = $shortCode;

        return static::begin($config);
    }

    /**
     * Вернет контент шорткода, шорткод по названию [name]
     * @param $shortCode
     * @param null|string $sprintf
     * @param array $config
     * @return string
     * @throws \Exception
     */
    public static function get($shortCode, $sprintf = null, array $config = [])
    {
        $config['shortCode'] = $shortCode;
        $config['sprintf'] = $sprintf;
        return static::widget($config);
    }

    /**
     * Вернет модель по названию [name]
     * @param $shortCode
     * @return null|ShortCodeModel
     */
    public static function getModel($shortCode)
    {
        $key = static::VIEW_PARAMS_KEY;
        $view = Yii::$app->view;

        return isset($view->blocks[$key][$shortCode]) ? $view->blocks[$key][$shortCode] : null;
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var string
     */
    public $shortCode;

    /**
     * @var string
     */
    public $sprintf;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $shortCodes = $this->getShortCodes();

        if (!isset($shortCodes[$this->shortCode])) {
            return '';
        }

        $shortCode = $shortCodes[$this->shortCode];

        $content = $shortCode->content;
        if (!$content) {
            return '';
        }
// todo: глючат скрипты
//        $content = $this->contentShortCodes($content);

        if ($content && ($t = $this->sprintf)) {
            $content = sprintf($t, $content);
        }

        return $content;
    }

    /**
     * Заменит шорткоды в контенте
     * @param $content
     * @return string
     */
    protected function contentShortCodes($content)
    {
        $shortCodes = $this->getShortCodes();

        return preg_replace_callback('/\[(.*?)\]/uis', function ($matches) use ($shortCodes) {
            $return = '';
            if (!isset($shortCodes[$matches[1]])) {
                return $return;
            }

            $shortCode = $shortCodes[$matches[1]];
            if ($shortCode->type === ShortCodeModel::TYPE_GALLERY) {
                $files = $shortCode->sortFiles;
                foreach ($files as $file) {
                    $return .= sprintf('<img src="%s" alt="">', '/' . $file->url);
                }

                if ($return) {
                    $return = sprintf('<div 
                        class="fotorama" 
                        data-autoplay="true" 
                        data-loop="true" 
                        data-arrows="false" 
                        data-click="true" 
                        data-swipe="true">%s</div>', $return);

                    $this->getView()->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.css', [
                        'depends' => AppAsset::class,
                    ]);
                    $this->getView()->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.js', [
                        'depends' => AppAsset::class,
                    ]);
                }
            }

            return $return;
        }, $content);
    }

    /**
     * @return array|ShortCodeModel[]
     */
    protected function getShortCodes()
    {
        $key = static::VIEW_PARAMS_KEY;

        return isset($this->view->blocks[$key]) && ($t = $this->view->blocks[$key]) && is_array($t) ? $t : [];
    }
}
