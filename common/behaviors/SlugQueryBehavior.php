<?php

namespace common\behaviors;

use yii\base\Behavior;

/**
 * Class SlugQueryBehavior – Поведение для AR-моделей помогает делать выборку по полю slug
 *
 * @property \common\base\ActiveQuery $owner
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class SlugQueryBehavior extends Behavior
{
    /**
     * @var string – атрибут slug
     */
    public $slugAttribute = 'slug';

    /**
     * Добавит условие slug = $slug
     * @param $slug
     * @return \common\base\ActiveQuery
     */
    public function slug($slug)
    {
        return $this->owner->andWhere([$this->owner->getFullColumnName($this->slugAttribute) => $slug]);
    }

    /**
     * Поиск по полному URL / SLUG
     * @param $fullSlug
     * @param callable|null $callback
     * @return \common\base\ActiveQuery
     */
    public function fullSlug($fullSlug, callable $callback = null)
    {
        $fullSlugParts = explode('/', $fullSlug);

        $this->slug(array_pop($fullSlugParts));

        /**
         * @var $ownerModelClass \yii\db\ActiveRecord
         */
        $ownerModelClass = $this->owner->modelClass;

        $parentQuery = $ownerModelClass::find();
        if ($callback) {
            $callback($parentQuery);
        }
        $parentQuery->roots();
        $parentQuery->sort();
        $parentQuery->limit(1);
        $parent = $parentQuery->one();

        while ($parent && $slug = array_shift($fullSlugParts)) {
            $pq = $parent->children(1);
            if ($callback) {
                $callback($pq);
            }
            $pq->slug($slug);
            $parent = $pq->one();
            if (!$parent) {
                break;
            }
        }

        if ($parent) {
            $this->owner->andWhere(['>', $parent->leftAttribute, $parent->{$parent->leftAttribute}]);
            $this->owner->andWhere(['<', $parent->rightAttribute, $parent->{$parent->rightAttribute}]);
            $this->owner->andWhere([$parent->depthAttribute => $parent->{$parent->depthAttribute} + 1]);
        } else {
            $this->owner->andWhere('0=1');
        }

        return $this->owner;
    }
}
