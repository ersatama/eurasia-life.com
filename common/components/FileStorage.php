<?php

namespace common\components;

use yii\base\Component;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Class FileStorage – компонент помогает хранить файлы AR-моделей
 *
 * @package common\components
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FileStorage extends Component
{
    /**
     * @var array – список классов моделей и ID-классов моделей
     */
    public $modelClassIdMap;

    /**
     * Вернет ID класса модели по экземпляру модели
     * ID нужно, чтоб знать новость это или объява и т.д.
     * @param ActiveRecord $owner
     * @return integer
     * @throws Exception
     */
    public function getOwnerClassIdByOwnerInstance(ActiveRecord $owner)
    {
        foreach ($this->modelClassIdMap as $modelClass => $modelClassId) {
            if (($owner instanceof $modelClass)) {
                return $modelClassId;
            }
        }

        throw new Exception(sprintf('Owner class id for class `%s` not found', get_class($owner)));
    }
}
