<?php

namespace backend\models;

use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;
use common\behaviors\NotifyBehavior;
use common\models\Language;
use common\models\PostArticle;

/**
 * Class PostArticleForm – Форма Публикации
 *
 * @mixin NotifyBehavior
 *
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostArticleForm extends Model
{
    const SCENARIO_CREATE = 'create';

    const PUBLISH_DATE_FORMAT = 'd.m.Y';

    const PUBLISH_TIME_FORMAT = 'H:i';

    const IMAGE_TYPES = [
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/gif',
    ];

    const MAIN_IMAGE_TYPES = self::IMAGE_TYPES;

    const SOC_IMAGE_TYPES = self::IMAGE_TYPES;

    const UPLOAD_FILE_FIELDS = [
        'main_image',
        'soc_image',
    ];

    const BTN_REMOVE_MAIN_IMAGE = 'main';

    const BTN_REMOVE_SOC_IMAGE = 'soc';

    /**
     * Создает временную запись, чтоб можно было добавить публикацию в админке
     * @return PostArticle
     * @throws Exception
     */
    public static function createTmp()
    {
        // ru
        $postArticle = new PostArticle();
        $postArticle->lang_id = Language::ID_RU;
        $postArticle->publish_at = time();
        $postArticle->changeStatusToCreate();
        $postArticle->setCreatedAttributes();
        $postArticle->saveWithException(false);

        // kz
        $kzPostArticle = new PostArticle();
        $kzPostArticle->lang_id = Language::ID_KZ;
        $kzPostArticle->publish_at = time();
        $kzPostArticle->changeStatusToCreate();
        $kzPostArticle->setCreatedAttributes();
        $kzPostArticle->saveWithException(false);

        // en
        $enPostArticle = new PostArticle();
        $enPostArticle->lang_id = Language::ID_EN;
        $enPostArticle->publish_at = time();
        $enPostArticle->changeStatusToCreate();
        $enPostArticle->setCreatedAttributes();
        $enPostArticle->saveWithException(false);

        $enPostArticle->lang_ru_id = $postArticle->id;
        $enPostArticle->lang_kz_id = $kzPostArticle->id;
        $enPostArticle->saveWithException(false);

        $kzPostArticle->lang_ru_id = $postArticle->id;
        $kzPostArticle->lang_en_id = $enPostArticle->id;
        $kzPostArticle->saveWithException(false);

        $postArticle->lang_kz_id = $kzPostArticle->id;
        $postArticle->lang_en_id = $enPostArticle->id;
        $postArticle->saveWithException(false);

        return $postArticle;
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

//    /**
//     * @var string - Анонс
//     */
//    public $announce;

    /**
     * @var string - Контент
     */
    public $content;

    /**
     * @var string – Дата публикации
     */
    public $publish_date;

    /**
     * @var string – Время публикации
     */
    public $publish_time;

    /**
     * @var UploadedFile - главная фотка
     */
    public $main_image;

    /**
     * @var string - путь до главной фотки
     */
    public $main_image_url;

    /**
     * @var boolean - Показывать на сайте
     */
    public $visible;

    /**
     * @var string - Тайтл для соц сетей
     */
    public $soc_title;

    /**
     * @var string - Контент для соц сетей
     */
    public $soc_content;

    /**
     * @var UploadedFile – Картинка для соц сети
     */
    public $soc_image;

    /**
     * @var string — кнопка удалить картинку
     */
    public $btn_remove_image;

    /**
     * @var PostArticle – публикация с которым работаем
     */
    public $postArticle;

    /**
     * @var self[]
     */
    protected $langForms = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $postArticle = $this->postArticle;

        $this->setAttributes($postArticle->getAttributes([
            'name',
            'slug',
//            'announce',
            'content',
            'main_image_url',
            'visible',
            'soc_title',
            'soc_content',
        ]), false);

        $this->publish_date = ($t = $postArticle->publish_at) ? date(static::PUBLISH_DATE_FORMAT, $t) : '';

        $this->publish_time = ($t = $postArticle->publish_at) ? date(static::PUBLISH_TIME_FORMAT, $t) : '';

        parent::init();
    }

    /**
     * иниц формы с переводами
     */
    public function initLangForms()
    {
        foreach ($this->postArticle->findAllTranslations() as $postArticle) {
            $this->langForms[$postArticle->lang_id] = new self(['postArticle' => $postArticle]);
        }
    }

    /**
     * @return PostArticleForm[]
     */
    public function getLangForms()
    {
        return $this->langForms;
    }

    /**
     * Установит сценарий создания
     */
    public function setScenarioCreate()
    {
        $this->scenario = PostArticleForm::SCENARIO_CREATE;
        foreach ($this->getLangForms() as $langForm) {
            $langForm->scenario = PostArticleForm::SCENARIO_CREATE;
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
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        foreach ($this->langForms as $langForm) {
            $langForm->load($data);
        }
        return parent::load($data, $formName);
    }

    /**
     * Сохраняет публикацию (Создает, Обновляет)
     * @return bool
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function save()
    {
        if (!$this->saveLangForms()) {
            return false;
        }

        $postArticle = $this->getPostArticle();

        if ($this->saveUnlinkImage($postArticle)) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $postArticle->setAttributes($this->getAttributes([
            'name',
            'slug',
//            'announce',
            'content',
            'main_image_url',
            'visible',
            'soc_title',
            'soc_content',
        ]), false);

        $postArticle->publish_at = strtotime($this->publish_date . ' ' . $this->publish_time . ':00');

        $this->saveUploadedFiles($postArticle);

        if ($this->scenario === static::SCENARIO_CREATE) {
            $postArticle->setCreatedAttributes();
            $postArticle->changeStatusToActive();
            $postArticle->backupCreate();
        } else {
            $postArticle->setUpdatedAttributes();
            $postArticle->backupUpdate();
        }

        return $postArticle->saveWithException();
    }

    /**
     * Сохраняем загруженные файлы
     * @param PostArticle $postArticle
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function saveUploadedFiles(PostArticle $postArticle)
    {
        if ($this->main_image) {
            $file = $postArticle->linkUploadedMainImage($this->main_image);
            if ($file) {
                $file->imageOptimize();
            }
        }

        if ($this->soc_image) {
            $file = $postArticle->linkUploadedFile($this->soc_image, 'soc_image_id');
            if ($file) {
                $file->imageOptimize();
            }
        }
    }

    /**
     * Удаляем привязанные картинки
     * @param PostArticle $postArticle
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    protected function saveUnlinkImage(PostArticle $postArticle)
    {
        $btnRemoveImage = $this->btn_remove_image;

        if (!$btnRemoveImage) {
            return false;
        }

        if ($this->validate('btn_remove_image')) {
            if ($btnRemoveImage === static::BTN_REMOVE_MAIN_IMAGE) {
                $postArticle->unlinkMainImage();
                $this->notifySuccess('Главная картинка успешно удалена');
            }

            if ($btnRemoveImage === static::BTN_REMOVE_SOC_IMAGE) {
                $postArticle->unlinkFileByFieldName('soc_image_id');
                $this->notifySuccess('Картинка для соц сетей успешно удалена');
            }
        }

        return true;
    }

    /**
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function saveLangForms()
    {
        foreach ($this->langForms as $langForm) {
            if (!$langForm->save()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Вернет публикацию с которой работаем
     * @return PostArticle
     */
    public function getPostArticle()
    {
        return $this->postArticle;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name
//            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 255],

            // slug
//            ['slug', 'required'],
            ['slug', 'trim'],
            ['slug', 'string', 'max' => 255],
            ['slug', 'filter', 'filter' => 'mb_strtolower'],
// @todo: unique slug
//            [
//                'slug',
//                'unique',
//                'targetClass' => PostArticle::class,
//                'filter' => function (PostArticleQuery $query) {
//                    $query->andWhere(['!=', 'id', $this->getId()]);
//                    $query->limit(1);
//                },
//            ],

//            // announce
//            ['announce', 'trim'],
//            ['announce', 'string', 'max' => 65000],

            // content
            ['content', 'trim'],
            ['content', 'string', 'max' => 65000],

            // publish_date
//            ['publish_date', 'required'],
            ['publish_date', 'date', 'format' => 'php:' . static::PUBLISH_DATE_FORMAT],

            // publish_time
//            ['publish_time', 'required'],
            ['publish_time', 'date', 'format' => 'php:' . static::PUBLISH_TIME_FORMAT],

            // main_image
            ['main_image', 'image', 'mimeTypes' => static::MAIN_IMAGE_TYPES],

            // main_image_url
            ['main_image_url', 'string', 'max' => 255],

            // soc_title
            ['soc_title', 'trim'],
            ['soc_title', 'string', 'max' => 255],

            // soc_content
            ['soc_content', 'trim'],
            ['soc_content', 'string', 'max' => 65000],

            // soc_image
            ['soc_image', 'image', 'mimeTypes' => static::SOC_IMAGE_TYPES],

            // visible
            ['visible', 'filter', 'filter' => 'intval'],
            ['visible', 'boolean'],

            //btn_remove_image
            ['btn_remove_image', 'in', 'range' => [
                static::BTN_REMOVE_MAIN_IMAGE,
                static::BTN_REMOVE_SOC_IMAGE,
            ]],
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
            'id' => 'ID-новости',
            'name' => 'Наименование',
            'slug' => 'URL',
//            'announce' => 'Анонс',
            'content' => 'Контент',
            'publish_date' => 'Дата публикации',
            'publish_time' => 'Время публикации',
            'main_image' => 'Главная картинка',
            'main_image_url' => 'УРЛ до главной картинки',
            'soc_title' => 'Заголовок для ссылки на сайт',
            'soc_content' => 'Подзаголовок',
            'soc_image' => 'Картинка',
            'visible' => 'Показывать на сайте',
            'views' => 'Просмотров',
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'post-article-' . $this->postArticle->id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getPostArticle()->getPrimaryKey();
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->getPostArticle()->views;
    }

    /**
     * @return \common\models\File
     */
    public function getSocImage()
    {
        return $this->getPostArticle()->socImage;
    }

    /**
     * @param int $langId
     * @return PostArticleForm|null
     */
    public function getLangFormByLangId(int $langId): ?self
    {
        return $this->langForms[$langId] ?? null;
    }
}
