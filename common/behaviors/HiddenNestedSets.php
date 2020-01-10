<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\ActiveRecord;

/**
 * Class HiddenNestedSets – Видимость записей дерева.
 * - если скрыли родителя, то скрываем потомков
 * - если показали потомка, то показываем его родителей
 *
 * @property yii\db\ActiveRecord $owner
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class HiddenNestedSets extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * После сохранения родителя (основной модели)
     * @param AfterSaveEvent $event
     */
    public function afterSave(AfterSaveEvent $event)
    {
        $changedAttributes = $event->changedAttributes;

        if (isset($changedAttributes['visible'])) {
            $owner = $this->owner;

            if ($changedAttributes['visible'] && !$owner->visible) {
                $attributes = ['visible' => 0];
                $condition = ['and', ['>', 'lft', $owner->lft], ['<', 'rgt', $owner->rgt]];
            } else {
                $attributes = ['visible' => 1,];
                $condition = ['and', ['<', 'lft', $owner->lft], ['>', 'rgt', $owner->rgt]];
            }

            /**
             * @var $ownerClassname ActiveRecord
             */
            $ownerClassname = get_class($owner);
            $ownerClassname::updateAll($attributes, $condition);
        }
    }
}
