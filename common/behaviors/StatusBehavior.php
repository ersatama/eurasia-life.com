<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;

/**
 * Class StatusBehavior – Поведение для AR-моделей
 * - Модели получают статусы (активен, создается, удален)
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class StatusBehavior extends Behavior
{
    // неизвестный статус. желательно избегать такого!
    const STATUS_UNKNOWN = 0;

    // активная запись. можно безопасно работать с ней
    const STATUS_ACTIVE = 1;

    // удалили. нигде не показываем.
    const STATUS_DELETE = 2;

    // создаем. запись еще не готова, нигде не показываем, кроме подачи.
    const STATUS_CREATE = 3;

    /**
     * Вернет названия для статусов, также помогает проверять существования статуса
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_UNKNOWN => 'неизвестный',
            self::STATUS_ACTIVE => 'активен',
            self::STATUS_DELETE => 'удален',
            self::STATUS_CREATE => 'создается',
        ];
    }

    /**
     * Существует такой статус или нет
     * @param $status
     * @return bool
     */
    public static function statusExists($status)
    {
        return ($statusLabels = static::getStatusLabels()) && isset($statusLabels[$status]);
    }

    /**
     * Существует такой статус или нет
     * - если нет, то бросим Exception
     * @param $status
     */
    public static function statusExistsWithException($status)
    {
        if (!static::statusExists($status)) {
            throw  new InvalidArgumentException(sprintf('Status `%s` not found', $status));
        }
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var \yii\db\ActiveRecord
     */
    public $owner;

    /**
     * @var string – статус
     */
    public $statusAttribute = 'status';

    /**
     * @var string – кто сменил статус
     */
    public $statusByAttribute = 'status_by';

    /**
     * @var string – когда сменили статус
     */
    public $statusAtAttribute = 'status_at';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array_merge(parent::events(), [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSaveCheckStatus',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSaveCheckStatus',
        ]);
    }

    /**
     * Перед тем как сохранить модель, проверит существование указанного статуса
     */
    public function beforeSaveCheckStatus()
    {
        static::statusExistsWithException($this->owner->{$this->statusAttribute});
    }

    /**
     * Проверит тек. статус = $status
     * @param $status
     * @return bool
     */
    public function statusIs($status)
    {
        static::statusExistsWithException($status);

        return $this->owner->{$this->statusAttribute} == $status;
    }

    /**
     * Смена статуса
     * @param $newStatus
     * @return ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public function changeStatusTo($newStatus)
    {
        static::statusExistsWithException($newStatus);

        $this->owner->{$this->statusAttribute} = $newStatus;
        $this->owner->{$this->statusAtAttribute} = $this->getAtValue();
        $this->owner->{$this->statusByAttribute} = $this->getByValue();

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

    /**
     * Тек. статус == Создается
     * @return bool
     */
    public function statusIsCreate()
    {
        return $this->statusIs(static::STATUS_CREATE);
    }

    /**
     * Тек. статус == Активен
     * @return bool
     */
    public function statusIsActive()
    {
        return $this->statusIs(static::STATUS_ACTIVE);
    }

    /**
     * Тек. статус == Удален
     * @return bool
     */
    public function statusIsDelete()
    {
        return $this->statusIs(static::STATUS_DELETE);
    }

    /**
     * Сменить статус на активный
     * @return ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public function changeStatusToActive()
    {
        return $this->changeStatusTo(static::STATUS_ACTIVE);
    }

    /**
     * Сменить статус на "создается"
     * @return ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public function changeStatusToCreate()
    {
        return $this->changeStatusTo(static::STATUS_CREATE);
    }

    /**
     * Сменить статус на "удален"
     * @return ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public function changeStatusToDelete()
    {
        return $this->changeStatusTo(static::STATUS_DELETE);
    }

    /**
     * Фейковое удаление модели. Укажет статус "удален"
     * @return null|\yii\base\Component
     * @throws \yii\base\InvalidConfigException
     */
    public function fakeDelete()
    {
        $this->changeStatusToDelete();

        return $this->saveOnlyStatusAttributes();
    }

    /**
     * Сохранит только статусные поля
     * @return null|\yii\base\Component
     */
    public function saveOnlyStatusAttributes()
    {
        $this->owner->save(true, [
            $this->statusAttribute,
            $this->statusAtAttribute,
            $this->statusByAttribute,
        ]);

        return $this->owner;
    }
}
