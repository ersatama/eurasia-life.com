<?php

namespace common\caching;

use common\models\PostArticle;

/**
 * Class ArticleCallback
 *
 * @package common\caching
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ArticleCallback
{
    /**
     * @var int
     */
    private static $cache;

    /**
     * @return int
     */
    public static function run(): int
    {
        return static::$cache === null ? (static::$cache = static::getData()) : static::$cache;
    }

    /**
     * @return int
     */
    protected static function getData(): int
    {
        $q = new \yii\db\Query();
        $q->select(['IFNULL(MAX(`updated_at`), 0)']);
        $q->from(PostArticle::tableName());
        $q->cache(10);

        return (int)$q->scalar(PostArticle::getDb());
    }
}
