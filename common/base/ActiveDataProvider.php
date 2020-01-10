<?php

namespace common\base;

/**
 * Class ActiveDataProvider – Провайдер данных
 * - помогает подменить модели через callback, полезно когда ставят поисковую машину
 *
 * @package common\base
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ActiveDataProvider extends \yii\sphinx\ActiveDataProvider
{
    /**
     * @var callable
     */
    public $prepareModelsCallback;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    protected function prepareModels()
    {
        $return = parent::prepareModels();

        return is_callable($this->prepareModelsCallback) ? call_user_func($this->prepareModelsCallback, $return) : $return;
    }
}
