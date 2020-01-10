<?php

namespace common\behaviors;

use yii\base\Behavior;

/**
 * Class VisibleQueryBehavior – Поведение помогает делать выборку по полю видимости на сайте (visible)
 *
 * @property \common\base\ActiveQuery $owner
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class VisibleQueryBehavior extends Behavior
{
    /**
     * @var string – атрибут visible
     */
    public $visibleAttribute = 'visible';

    /**
     * Добавит условие visible = 1
     * @return \common\base\ActiveQuery
     */
    public function visible()
    {
        return $this->owner->andWhere([$this->owner->getFullColumnName($this->visibleAttribute) => 1]);
    }

    /**
     * Добавит условие visible = true, если $if верно
     * @param bool $if
     * @return \common\base\ActiveQuery
     */
    public function visibleCheckIf($if)
    {
        return $if ? $this->visible() : $this->owner;
    }
}
