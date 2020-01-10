<?php

namespace common\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use common\behaviors\Backup;
use common\behaviors\CreatedUpdatedBehavior;
use common\behaviors\FilesBehavior;
use common\behaviors\HiddenNestedSets;
use common\behaviors\StatusBehavior;
use common\behaviors\ViewsBehavior;
use common\traits\CommonAttrsTrait;
use common\traits\SaveWithExceptionTrait;
use common\traits\TitleOrNameTrait;
use common\traits\Urls;

/**
 * Class Page – Модель Контент-страницы
 *
 * This is the model class for table "{{%page}}".
 *
 * @property string $slug
 * @property string $name
 * @property string $title
 * @property string $body
 * @property boolean $visible
 * @property integer $views
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 *
 * @mixin Backup
 * @mixin FilesBehavior
 * @mixin HiddenNestedSets
 * @mixin NestedSetsBehavior
 * @mixin ViewsBehavior
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Page extends ActiveRecord
{
    use CommonAttrsTrait,
        SaveWithExceptionTrait,
        TitleOrNameTrait,
        Urls;

    /**
     * @inheritdoc
     * @return PageQuery
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }

    /**
     * @var static[]
     */
    protected static $all_pages;

    /**
     * @return static[]
     */
    public static function getAllPages()
    {
        return static::$all_pages === null ? (static::$all_pages = self::findAllPages()) : static::$all_pages;
    }

    /**
     * @param static[] $pages
     */
    public static function setAllPages(array $pages)
    {
        static::$all_pages = $pages;
    }

    /**
     * @return array|Page[]|ActiveRecord[]
     */
    public static function findAllPages()
    {
        $query = static::find();
        $query->active();
        $query->withoutRoot();
        $query->sort();

        return $query->all();
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            Backup::class,
            CreatedUpdatedBehavior::class,
            FilesBehavior::class,
            HiddenNestedSets::class,
            NestedSetsBehavior::class,
            StatusBehavior::class,
            ViewsBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * Если нет root-записи то создаст ее и добавит в конец текущ запись
     * @return $this
     */
    public function appendToRoot()
    {
        /**
         * @var $root static
         */
        $root = static::find()->roots()->one();
        if (!($root instanceof static)) {
            $root = new static();
            $root->slug = 'stranica';
            $root->name = 'Страницы';
            $root->visible = true;
            $root->changeStatusToActive();
            $root->setCreatedAttributes();
            $root->makeRoot();
        }

        $this->appendTo($root);

        return $this;
    }

    /**
     * Вернет путь до страницы на сайте
     * @return string
     */
    public function getUrl()
    {
        $slug = [];

//        /**
//         * @var $query PageQuery
//         */
//        $query = $this->parents();
//        $query->withoutRoot();
//        $query->active();
//        $query->sort();
//
//        $parents = $query->all();
//        foreach ($parents as $parent) {
//            $slug[] = $parent->slug;
//        }

        foreach (static::getAllPages() as $page) {
            if ($this->isChildOf($page)) {
                $slug[] = $page->slug;
            }
        }

        $slug[] = $this->slug;

        return '/' . implode('/', $slug);
    }
}
