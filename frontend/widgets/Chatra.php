<?php

namespace frontend\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\web\View;
use common\helpers\Html;
use common\widgets\Script;

/**
 * Class Chatra – Подключает https://chatra.io
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Chatra extends Widget
{
    /**
     * @var string|null - название ссылки
     */
    public $linkName;

    /**
     * @var array|string - опции для ссылки
     */
    public $linkOptions;

    /**
     * @var string – Обвернет через sprintf
     */
    public $block;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (!YII_ENV_PROD) {
            return '';
        }
        $this->registerJs();

        return $this->linkName ? $this->returnLink() : '';
    }

    /**
     * @return string
     */
    protected function returnLink()
    {
        $options = $this->linkOptions;
        if (!is_array($options)) {
            $options = [
                'class' => $options,
            ];
        }
        $options['href'] = '#';
        $options['onclick'] = 'Chatra("openChat", true); return false;';

        $content = Html::a($this->linkName, null, $options);

        return $this->block ? sprintf($this->block, $content) : $content;
    }

    /**
     * Регистрация js скриптов
     * @throws InvalidConfigException
     */
    protected function registerJs()
    {
        Script::begin(['position' => View::POS_HEAD]); ?>
        <!-- Chatra {literal} -->
        <script>
            (function (d, w, c) {
                w.ChatraID = '<?= $this->getChatraId() ?>';
                var s = d.createElement('script');
                w[c] = w[c] || function () {
                    (w[c].q = w[c].q || []).push(arguments);
                };
                s.async = true;
                s.src = (d.location.protocol === 'https:' ? 'https:' : 'http:')
                    + '//call.chatra.io/chatra.js';
                if (d.head) d.head.appendChild(s);
            })(document, window, 'Chatra');
        </script>
        <!-- /Chatra {/literal} -->
        <?php Script::end();
    }

    /**
     * Вернет id-чатры
     * @return string
     * @throws InvalidConfigException
     */
    public function getChatraId()
    {
        if (!isset(Yii::$app->params['chatraId'])) {
            throw new InvalidConfigException(sprintf('Chatra id not found'));
        }

        return Yii::$app->params['chatraId'];
    }
}
