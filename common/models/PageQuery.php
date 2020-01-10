<?php

namespace common\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use common\base\ActiveQuery;
use common\behaviors\SlugQueryBehavior;
use common\behaviors\StatusQueryBehavior;
use common\behaviors\VisibleQueryBehavior;

/**
 * Class PageQuery – Выборка Контент-страниц
 *
 * @mixin NestedSetsQueryBehavior
 * @mixin SlugQueryBehavior
 * @mixin StatusQueryBehavior
 * @mixin VisibleQueryBehavior
 *
 * @method Page|\yii\db\ActiveRecord|array|null one($db = null)
 * @method Page[]|array|\yii\db\ActiveRecord[] all($db = null)
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PageQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::class,
            SlugQueryBehavior::class,
            StatusQueryBehavior::class,
            VisibleQueryBehavior::class,
        ];
    }

    /**
     * Добавить в условие "depth > 0" – Без root-записей
     * @return $this
     */
    public function withoutRoot()
    {
        return $this->andWhere(['>', 'depth', 0]);
    }

    /**
     * Добавит сортировку по-умолчанию
     * - сортируем по левому ключу
     * @return $this
     */
    public function sort()
    {
        return $this->orderBy('lft asc');
    }
}
