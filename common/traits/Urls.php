<?php

namespace common\traits;

use Yii;

/**
 * Class Urls – Трейт УРЛов моделей
 *
 * @property \yii\db\ActiveRecord $this
 * @property string $url
 * @property string $fullUrl
 * @property string $urlSimSim
 * @property string $fullUrlSimSim
 *
 * @package common\traits
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait Urls
{
    /**
     * Вернет путь до записи на сайте с ключом "Показывать неопубликованные записи"
     * @return string
     */
    public function getUrlSimSim()
    {
        return call_user_func_array([$this, 'getUrl'], func_get_args()) . '?sim-sim';
    }

    /**
     * Вернет полный путь до записи
     * @return string
     */
    public function getFullUrl()
    {
        return Yii::getAlias('@frontendWeb') . call_user_func_array([$this, 'getUrl'], func_get_args());
    }

    /**
     * Вернет полный путь до записи с ключом Показывать скрытые записи.
     * @return string
     */
    public function getFullUrlSimSim()
    {
        return Yii::getAlias('@frontendWeb') . call_user_func_array([$this, 'getUrlSimSim'], func_get_args());
    }

    /**
     * @param $params
     * @return string
     */
    protected function createFrontendUrl($params)
    {
        $app = \Yii::$app;
        $urlManager = isset($app->frontendUrlManager) && ($t = $app->frontendUrlManager) instanceof \yii\web\UrlManager
            ? $t : $app->urlManager;
        return $urlManager->createUrl($params);
    }
}
