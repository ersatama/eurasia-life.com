<?php

namespace common\behaviors;

use yii\base\Behavior;

/**
 * Class StatusQueryBehavior – Поведение помогает делать выборку по статусам AR-модели
 *
 * @mixin \common\base\ActiveQuery
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class StatusQueryBehavior extends Behavior
{
    /**
     * @var \common\base\ActiveQuery
     */
    public $owner;

    /**
     * Добавит условие status = $status
     * @param $status
     * @return \common\base\ActiveQuery
     */
    public function status($status)
    {
        return $this->owner->andWhere([$this->owner->getFullColumnName('status') => $status]);
    }

    /**
     * Добавит условие status IN($status)
     * @param array $statuses
     * @return \common\base\ActiveQuery
     */
    public function statusIn(array $statuses)
    {
        return $this->owner->andWhere(['in', 'status', $statuses]);
    }

    /**
     * Добавит условие status = 'active'
     * @return \common\base\ActiveQuery
     */
    public function active()
    {
        return $this->status(StatusBehavior::STATUS_ACTIVE);
    }

    /**
     * Добавит условие status = 'create'
     * @return \common\base\ActiveQuery
     */
    public function statusCreate()
    {
        return $this->status(StatusBehavior::STATUS_CREATE);
    }

    /**
     * Добавит условие status IN('active', 'create')
     * @return \common\base\ActiveQuery
     */
    public function activeOrCreate()
    {
        return $this->statusIn([
            StatusBehavior::STATUS_ACTIVE,
            StatusBehavior::STATUS_CREATE,
        ]);
    }

    /**
     * Поиск одной активной записи по id
     * @param int $id
     * @return null|\yii\db\ActiveRecord
     */
    public function oneActiveById($id)
    {
        return $this->active()->id($id)->limit(1)->one();
    }

    /**
     * Поиск одной активной или только созданной записи по id
     * @param int $id
     * @return null|\yii\db\ActiveRecord
     */
    public function oneActiveOrCreateById($id)
    {
        return $this->statusIn([
            StatusBehavior::STATUS_ACTIVE,
            StatusBehavior::STATUS_CREATE
        ])->id($id)->limit(1)->one();
    }
}
