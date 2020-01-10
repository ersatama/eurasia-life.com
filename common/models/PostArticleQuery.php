<?php

namespace common\models;

use common\base\ActiveQuery;
use common\behaviors\SlugQueryBehavior;
use common\behaviors\StatusQueryBehavior;
use common\behaviors\VisibleQueryBehavior;

/**
 * Class PostArticleQuery – Выборка Публикаций
 *
 * @mixin SlugQueryBehavior
 * @mixin StatusQueryBehavior
 * @mixin VisibleQueryBehavior
 *
 * @method PostArticle|\yii\db\ActiveRecord|array|null one($db = null)
 * @method PostArticle[]|array|\yii\db\ActiveRecord[] all($db = null)
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostArticleQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            SlugQueryBehavior::class,
            StatusQueryBehavior::class,
            VisibleQueryBehavior::class,
        ];
    }

    /**
     * @return PostArticleQuery
     */
    public function withMainImage(): PostArticleQuery
    {
        return $this->with('mainImage');
    }

    /**
     * @param int $time
     * @return PostArticleQuery
     */
    public function published(int $time = null): PostArticleQuery
    {
        $time === null && $time = time();

        return $this->andWhere(['<=', 'publish_at', $time]);
    }

    /**
     * @return PostArticleQuery
     */
    public function sort(): PostArticleQuery
    {
        return $this->sortByPosition();
    }

    /**
     * @return PostArticleQuery
     */
    public function sortByPosition(): PostArticleQuery
    {
        return $this->orderBy(['position' => SORT_DESC]);
    }

    /**
     * @return PostArticleQuery
     */
    public function sortByPublishAt(): PostArticleQuery
    {
        return $this->orderBy(['publish_at' => SORT_DESC]);
    }

    /**
     * @param bool|int $cacheTime
     */
    public function cacheWithDependency($cacheTime = true)
    {
        $this->cache($cacheTime, new \common\caching\CallbackDependency(['callback' => '\common\caching\ArticleCallback::run']));
    }
}
