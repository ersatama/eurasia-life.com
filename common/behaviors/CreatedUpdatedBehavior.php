<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class CreatedUpdatedBehavior — Поведение для AR-моделей
 * - помогает указывать кто и когда создал или обновил модель
 * - от \yii\behaviors\TimestampBehavior и \yii\behaviors\BlameableBehavior отличается тем,
 *   что нужно явно указать что модель создалась или обновилась, полезно для админки
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class CreatedUpdatedBehavior extends Behavior
{
    /**
     * @var string – когда создали
     */
    public $createdAtAttribute = 'created_at';

    /**
     * @var string – кто создал
     */
    public $createdByAttribute = 'created_by';

    /**
     * @var string – когда обновили
     */
    public $updatedAtAttribute = 'updated_at';

    /**
     * @var string – кто обновил
     */
    public $updatedByAttribute = 'updated_by';

    /**
     * Установит атрибуты создания
     * @return \yii\base\Component the owner of this behavior
     * @throws \yii\base\InvalidConfigException
     */
    public function setCreatedAttributes()
    {
        return $this->setAtByAttributes($this->createdAtAttribute, $this->createdByAttribute);
    }

    /**
     * Установит атрибуты обновления
     * @return \yii\base\Component the owner of this behavior
     * @throws \yii\base\InvalidConfigException
     */
    public function setUpdatedAttributes()
    {
        return $this->setAtByAttributes($this->updatedAtAttribute, $this->updatedByAttribute);
    }

    /**
     * Установит указанные поля
     * @param string $at
     * @param string $by
     * @return \yii\base\Component the owner of this behavior
     * @throws \yii\base\InvalidConfigException
     */
    protected function setAtByAttributes($at, $by)
    {
        $this->owner->{$at} = $this->getAtValue();
        $this->owner->{$by} = $this->getByValue();

        return $this->owner;
    }

    /**
     * Значение для at атрибута
     * @return int
     */
    protected function getAtValue()
    {
        return time();
    }

    /**
     * Значение для by атрибута
     * @return null|int
     * @throws \yii\base\InvalidConfigException
     */
    protected function getByValue()
    {
        return ($user = Yii::$app->get('user', false)) && !$user->isGuest ? $user->id : null;
    }
}
