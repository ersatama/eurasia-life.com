<?php

namespace common\traits;

use yii\base\Exception;

/**
 * Class SaveWithExceptionTrait – Помогает сохранять модели с выбросом исключения, если что-то пошло не так
 *
 * @mixin \yii\db\ActiveRecord
 *
 * @package common\traits
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait SaveWithExceptionTrait
{
    /**
     * Сохраняем AR-модель
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     */
    public function saveWithException($runValidation = true, $attributeNames = null)
    {
        if (!$this->save($runValidation, $attributeNames)) {
            throw new Exception(sprintf(
                'Model `%s` not saved. Errors: %s',
                get_class($this),
                json_encode($this->getErrors())
            ));
        }

        return true;
    }
}
