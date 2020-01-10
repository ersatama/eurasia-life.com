<?php

namespace common\widgets;

use yii\bootstrap\ActiveForm as BaseActiveForm;
use yii\base\Model;
use common\helpers\Html;

/**
 * Class ActiveForm – Расшираем возможности
 * - показываем alert-сообщения пользователям
 *
 * @package common\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ActiveForm extends BaseActiveForm
{
    /**
     * @param Model $model
     * @param null $position
     * @return string
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function notifies(Model $model, $position = null)
    {
        $position = $model->formName() . $position;

        return Notify::widget(['position' => $position]);
    }

    /**
     * Кнопка модели
     * @param $model
     * @param $title
     * @param $name
     * @param int $value
     * @param array $options
     * @return string
     */
    public function button($model, $title, $name, $value = 1, array $options = [])
    {
        $name = Html::getInputName($model, $name);

        $options = array_merge([
            'name' => $name,
            'value' => $value,
            'class' => 'btn btn-primary',
        ], $options);

        return Html::submitButton($title, $options);
    }
}
