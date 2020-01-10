<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\traits\SaveWithExceptionTrait;

/**
 * Class Backup – Модель Бэкапа
 *
 * This is the model class for table "{{%backup}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $data
 * @property string $old_data
 * @property string $action
 * @property integer $created_at
 * @property integer $created_by
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Backup extends ActiveRecord
{
    use SaveWithExceptionTrait;

    const ACTION_CREATE = 'create';

    const ACTION_UPDATE = 'update';

    const ACTION_DELETE = 'delete';

    const ACTION_DEFAULT = self::ACTION_UPDATE;

    /**
     * @param ActiveRecord $model
     * @param string $action
     * @param string|null $key
     * @return Backup
     * @throws \yii\base\InvalidConfigException
     */
    public static function createFromModel(ActiveRecord $model, string $action = self::ACTION_DEFAULT, string $key = null): self
    {
        if ($key === null) {
            $key = $model::tableName() . '-' . $model->id;
        }

        $data = $action == static::ACTION_DELETE ? null : $model->attributes;
        $old_data = $model->oldAttributes;

        return static::create($key, $data, $old_data, $action);
    }

    /**
     * @param string $key
     * @param $data
     * @param null $old_data
     * @param string $action
     * @return Backup
     * @throws \yii\base\InvalidConfigException
     */
    public static function create(string $key, $data, $old_data = null, string $action = self::ACTION_DEFAULT): self
    {
        $backup = new static();
        $backup->key = $key;
        $backup->data = $data;
        $backup->old_data = $old_data;
        $backup->action = $action;
        $backup->created_at = time();
        $backup->created_by = ($user = Yii::$app->get('user', false)) && !$user->isGuest ? $user->id : null;
        $backup->saveWithException();
        return $backup;
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->data = json_encode($this->data);
        $this->old_data = json_encode($this->old_data);
        return parent::beforeSave($insert);
    }
}
