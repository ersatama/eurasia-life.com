<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use common\models\Backup as BackupModel;

/**
 * Class Backup — Поведение помогает бэкапить модели
 *
 * @property \yii\db\ActiveRecord $owner
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Backup extends Behavior
{
    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array_merge(parent::events(), [
//            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
//            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ]);
    }
//
//    /**
//     * @throws \yii\base\InvalidConfigException
//     */
//    public function beforeSave()
//    {
//        BackupModel::createFromModel($this->owner);
//    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeDelete(): void
    {
        $this->backupDelete();
    }

    /**
     * @return BackupModel
     * @throws \yii\base\InvalidConfigException
     */
    public function backupCreate(): BackupModel
    {
        return $this->backup(BackupModel::ACTION_CREATE);
    }

    /**
     * @return BackupModel
     * @throws \yii\base\InvalidConfigException
     */
    public function backupUpdate(): BackupModel
    {
        return $this->backup(BackupModel::ACTION_UPDATE);
    }

    /**
     * @return BackupModel
     * @throws \yii\base\InvalidConfigException
     */
    public function backupDelete(): BackupModel
    {
        return $this->backup(BackupModel::ACTION_DELETE);
    }

    /**
     * @param $action
     * @return BackupModel
     * @throws \yii\base\InvalidConfigException
     */
    public function backup($action): BackupModel
    {
        return BackupModel::createFromModel($this->owner, $action);
    }
}
