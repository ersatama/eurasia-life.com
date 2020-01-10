<?php

namespace backend\models;

use yii\base\Exception;
use yii\base\Model;
use common\behaviors\NotifyBehavior;
use common\models\Page;
use common\models\PageQuery;

/**
 * Class PageForm – Форма контент-страницы
 *
 * @mixin NotifyBehavior
 *
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PageForm extends Model
{
    const SCENARIO_CREATE = 'create';

    const POSITION_TYPE_PARENT = 'parent';

    const POSITION_TYPE_AFTER = 'after';

    /**
     * Создает временную запись, чтоб можно было добавить страницу в админке
     * @return Page
     * @throws Exception
     */
    public static function createTmp()
    {
        $page = new Page();
        $page->changeStatusToCreate();
        $page->setCreatedAttributes();
        $page->appendToRoot();
        $page->saveWithException(false);

        return $page;
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var string – URL
     */
    public $slug;

    /**
     * @var string - Наименование
     */
    public $name;

    /**
     * @var string - Заголовок
     */
    public $title;

    /**
     * @var string - Контент
     */
    public $body;

    /**
     * @var boolean - Показывать на сайте
     */
    public $visible;

    /**
     * @var string – Тип связи (родитель, сосед)
     */
    public $position_type;

    /**
     * @var int – С кем связан
     */
    public $position_id;

    /**
     * @var Page – Страница с которой работаем
     */
    protected $page;

    /**
     * @inheritdoc
     * @param Page $page
     * @param array $config
     */
    public function __construct(Page $page, array $config = [])
    {
        $this->page = $page;

        $this->setAttributes($page->getAttributes([
            'slug',
            'name',
            'title',
            'body',
            'visible',
        ]), false);

        $this->initPositionFields();

        parent::__construct($config);
    }

    /**
     * Иниц полей позиции страницы
     */
    protected function initPositionFields()
    {
        $page = $this->page;

        /**
         * @var $pageQuery \common\models\PageQuery
         */
        $pageQuery = $page->prev();
        $pageQuery->active();
        $pageQuery->withoutRoot();

        $prevPage = $pageQuery->one();
        if ($prevPage) {
            $this->position_type = static::POSITION_TYPE_AFTER;
            $this->position_id = $prevPage->id;
        } else {
            $pageQuery = $page->parents(1);
            $pageQuery->active();
            $pageQuery->withoutRoot();

            $parentPage = $pageQuery->one();
            if ($parentPage) {
                $this->position_type = static::POSITION_TYPE_PARENT;
                $this->position_id = $parentPage->id;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NotifyBehavior::class,
        ];
    }

    /**
     * Сохраняет страницу (Создает, Обновляет)
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $page = $this->getPage();

        $page->setAttributes($this->getAttributes([
            'slug',
            'name',
            'title',
            'body',
            'visible',
        ]), false);

        if ($this->scenario === static::SCENARIO_CREATE) {
            $page->setCreatedAttributes();
            $page->changeStatusToActive();
            $page->backupCreate();
        } else {
            $page->setUpdatedAttributes();
            $page->backupUpdate();
        }

        return $this->saveWithSetPositionFields($page);
    }

    /**
     * Указывает позицию страницы
     * @param Page $page
     * @return bool
     * @throws Exception
     */
    protected function saveWithSetPositionFields(Page $page)
    {
        $positionModel = ($positionId = $this->position_id)
            ? Page::find()->withoutRoot()->id($positionId)->active()->one()
            : null;

        if ($positionModel) {
            return $this->position_type == static::POSITION_TYPE_PARENT
                ? $page->prependTo($positionModel)
                : $page->insertAfter($positionModel);
        }

        $pageQuery = Page::find();
        $pageQuery->active();
        $pageQuery->roots();
        $pageQuery->limit(1);
        $root = $pageQuery->one();
        if (!($root instanceof Page)) {
            throw new Exception(sprintf('Root not found.'));
        }
        return $page->prependTo($root);
    }

    /**
     * Вернет страницу с которой работаем
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // slug
            ['slug', 'required'],
            ['slug', 'trim'],
            ['slug', 'string', 'max' => 255],
            ['slug', 'filter', 'filter' => 'mb_strtolower'],
            [
                'slug',
                'unique',
                'targetClass' => Page::class,
                'filter' => function (PageQuery $query) {
                    $query->andWhere(['!=', 'id', $this->getId()]);
                    $query->limit(1);

                    $positionType = $this->position_type;
                    $positionId = $this->position_id;
                    if ($positionType && $positionId) {
                        /**
                         * @var $positionModel Page;
                         */
                        $positionModel = Page::find()->active()->id($this->position_id)->one();

                        if ($positionModel) {
                            if ($positionType === self::POSITION_TYPE_PARENT) {
                                $parentModel = $positionModel;
                            } else {
                                /**
                                 * @var $q PageQuery
                                 */
                                $q = $positionModel->parents(1);
                                $q->active();
                                $q->limit(1);
                                $parentModel = $q->one();
                            }
                            if ($parentModel) {
                                $query->andWhere(['depth' => $parentModel->depth + 1]);
                                $query->andWhere(['>', 'lft', $parentModel->lft]);
                                $query->andWhere(['<', 'rgt', $parentModel->rgt]);
                            }
                        }
                    }
                },
            ],

            // name
            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 255],

            // title
            ['title', 'trim'],
            ['title', 'string', 'max' => 255],

            // body
            ['body', 'trim'],

            // visible
            ['visible', 'filter', 'filter' => 'intval'],
            ['visible', 'boolean'],

            // position_type
            ['position_type', 'in', 'range' => [
                static::POSITION_TYPE_PARENT,
                static::POSITION_TYPE_AFTER,
            ]],
            // position_id
            ['position_id', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_CREATE] = $scenarios[static::SCENARIO_DEFAULT];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID-страницы',
            'slug' => 'URL',
            'name' => 'Наименование',
            'title' => 'Заголовок',
            'body' => 'Контент',
            'position_type' => 'Позиция (Связь)',
            'position_id' => 'Позиция (Модель)',
            'visible' => 'Показывать на сайте',
            'views' => 'Просмотров',
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'page';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getPage()->getPrimaryKey();
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->getPage()->views;
    }

    /**
     * Вернет массив данных для типа связи Позиции
     * @return array
     */
    public function getPositionTypeData()
    {
        return [
            static::POSITION_TYPE_PARENT => 'Родитель',
            static::POSITION_TYPE_AFTER => 'После',
        ];
    }

    /**
     * Вернет массив данных Моделей связи Позиции
     * @return array
     */
    public function getPageData()
    {
        $return = [];

        $pageQuery = Page::find();
        $pageQuery->active();
        $pageQuery->withoutRoot();
        $pageQuery->andWhere(['!=', 'id', $this->page->id]);
        $pageQuery->sort();
        $pages = $pageQuery->all();

        foreach ($pages as $page) {
            if ($page->isChildOf($this->page)) {
                continue;
            }
            $return[$page->id] = str_repeat('- ', $page->depth - 1) . $page->name;
        }

        return $return;
    }
}
