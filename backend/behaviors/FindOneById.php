<?php

namespace backend\behaviors;

use yii\base\Behavior;
use yii\web\NotFoundHttpException;

/**
 * Class FindOneById – Поведение для контроллеров
 * - помогает найти модель по ID
 * @package backend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FindOneById extends Behavior
{
    /**
     * @var string – Сообщения для исключения, что не нашли модель
     */
    public $notFoundMessage = 'Model with id `%s` not found';

    /**
     * Вернет модель по ID
     * @param $className string|\yii\db\ActiveRecordInterface – Класс модели
     * @param $modelId - ID модели
     * @param bool $orCreate – участвуют модели, которые только создали
     * @param bool $throwException – Выбрасывать исключение, если не нашли модель
     * @param null $notFoundMessage – Сообщения для исключения, что не нашли модель
     * @return null|$className
     * @throws NotFoundHttpException
     */
    public function findOneById($className, $modelId, $orCreate = false, $throwException = true, $notFoundMessage = null)
    {
        $model = null;

        if ($modelId) {
            $methodName = $orCreate ? 'oneActiveOrCreateById' : 'oneActiveById';
            $model = $className::find()->$methodName($modelId);
        }

        if (!$model && $throwException) {
            if ($notFoundMessage === null) {
                $notFoundMessage = $this->notFoundMessage;
            }
            throw new NotFoundHttpException(sprintf($notFoundMessage, $modelId));
        }

        return $model;
    }
}
