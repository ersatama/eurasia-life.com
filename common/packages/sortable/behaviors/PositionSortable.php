<?php

namespace common\packages\sortable\behaviors;

use yii\base\Behavior;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class PositionSortable — Поведение дает AR-моделям сортировку по позиции (целое число),
 * где position=1 - последний элемент, а max(position) первая позиция
 *
 * @property \yii\db\ActiveRecord $owner
 *
 * @package common\packages\sortable
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PositionSortable extends Behavior
{
    /**
     * @var string – Наименование атрибута в котором храним позиции
     */
    public $positionAttribute = 'position';

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        if (!$owner instanceof ActiveRecord) {
            $message = sprintf(
                'Behavior %s can only be attached to an instance of yii\db\ActiveRecord, %s given.',
                get_called_class(),
                is_object($owner) ? get_class($owner) : gettype($owner)
            );

            throw new InvalidArgumentException($message);
        }

        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array_merge(parent::events(), [
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
//            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'eventAfterDelete',
        ]);
    }

    /**
     * Событие до вставки записи
     */
    public function eventBeforeInsert()
    {
        $positionAttribute = $this->positionAttribute;
        $this->owner->$positionAttribute = $this->getMaxPosition() + 1;
    }

    /**
     * Событие после удалении записи
     * @throws \yii\db\Exception
     */
    public function eventAfterDelete()
    {
        $positionAttribute = $this->positionAttribute;
        $db = $this->getOwnerClassName()::getDb();

        $sql = $db->createCommand()->update($this->getOwnerClassName()::tableName(), [
            $positionAttribute => new Expression("[[$positionAttribute]]-1")
        ], [
            '>', $positionAttribute, $this->owner->$positionAttribute
        ])->getRawSql();
        $sql .= new Expression(" ORDER BY [[$positionAttribute]] ASC");
        $db->createCommand($sql)->execute();
    }

    /**
     * @throws \Throwable
     */
    public function moveAsFirst()
    {
        $query = $this->getOwnerClassName()::find();
        $query->orderBy([$this->positionAttribute => SORT_DESC]);
        $query->limit(1);
        $last = $query->one();

        if (!$last) {
            throw new Exception(sprintf('Last model not found'));
        }

        return $this->moveTo($last);
    }

    /**
     * @param ActiveRecord $prevModel
     * @return mixed
     * @throws \Throwable
     */
    public function moveAfter(ActiveRecord $prevModel)
    {
        $owner = $this->owner;
        $positionAttribute = $this->positionAttribute;

        if ($prevModel->$positionAttribute > $owner->$positionAttribute) {
            $prevModel = $prevModel->getNextModel();
        }

        return $this->moveTo($prevModel);
    }

    /**
     * @return array|ActiveRecord|null
     */
    public function getNextModel()
    {
        $owner = $this->owner;
        $query = $this->getNextModelQuery();

        return $query->one($owner->getDb());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNextModelQuery()
    {
        $owner = $this->owner;
        $positionAttribute = $this->positionAttribute;

        $query = $this->getOwnerClassName()::find();
        $query->andWhere(['<', $positionAttribute, $owner->$positionAttribute]);
        $query->orderBy([$positionAttribute => SORT_DESC]);
        $query->limit(1);

        return $query;
    }

    /**
     * @param ActiveRecord $prevModel
     * @return mixed
     * @throws \Throwable
     */
    protected function moveTo(ActiveRecord $prevModel)
    {
        $positionAttribute = $this->positionAttribute;

        return $this->move($this->owner->$positionAttribute, $prevModel->$positionAttribute);
    }

    /**
     * @param int $oldPosition
     * @param int $newPosition
     * @return mixed
     * @throws \Throwable
     */
    protected function move(int $oldPosition, int $newPosition)
    {
        return $this->owner->getDb()->transaction(function () use ($oldPosition, $newPosition) {
            if ($oldPosition == $newPosition) {
                return;
            }
            $owner = $this->owner;
            $positionAttribute = $this->positionAttribute;
            $db = $this->getOwnerClassName()::getDb();

            $owner->updateAttributes([$positionAttribute => null]);

            if ($newPosition < $oldPosition) {
                $sql = $db->createCommand()->update($this->getOwnerClassName()::tableName(), [
                    $positionAttribute => new Expression("[[$positionAttribute]]+1")
                ], [
                    'between', $positionAttribute, $newPosition, $oldPosition
                ])->getRawSql();
                $sql .= new Expression(" ORDER BY [[$positionAttribute]] DESC");
                $db->createCommand($sql)->execute();
            } else if ($newPosition > $oldPosition) {
                $sql = $db->createCommand()->update($this->getOwnerClassName()::tableName(), [
                    $positionAttribute => new Expression("[[$positionAttribute]]-1")
                ], [
                    'between', $positionAttribute, $oldPosition, $newPosition
                ])->getRawSql();
                $sql .= new Expression(" ORDER BY [[$positionAttribute]] ASC");
                $db->createCommand($sql)->execute();
            }

            $owner->updateAttributes([$positionAttribute => $newPosition]);
        });
    }

    /**
     * Вернет макс позицию
     * @return int
     */
    protected function getMaxPosition()
    {
        $query = new Query();
        $query->select(sprintf('MAX([[%s]])', $this->positionAttribute));
        $query->from($this->getOwnerClassName()::tableName());

        return (int)$query->scalar($this->owner->getDb());
    }

    /**
     * @return ActiveRecord
     */
    protected function getOwnerClassName()
    {
        return get_class($this->owner);
    }
}
