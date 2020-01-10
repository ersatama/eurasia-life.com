<?php

namespace common\behaviors;

use yii\base\Behavior;

/**
 * Class ViewsBehavior – Поведение для AR-модели помогает инкрементировать просмотры модели
 *
 * @property \yii\db\ActiveRecord $owner
 * @property int $views
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ViewsBehavior extends Behavior
{
    /**
     * @var string – атрибут views
     */
    public $viewsAttribute = 'views';

    /**
     * Инкрементируем просмотры
     * @return $this
     */
    public function incrementViews()
    {
        // подстрахуемся, "update ... views = views + 1" не работает с NULL
        $this->owner->{$this->viewsAttribute} === null
            ? $this->owner->updateAttributes([$this->viewsAttribute => 1])
            : $this->owner->updateCounters([$this->viewsAttribute => 1]);

        return $this;
    }
}
