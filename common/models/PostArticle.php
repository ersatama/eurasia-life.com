<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\packages\sortable\behaviors\PositionSortable;
use common\behaviors\Backup;
use common\behaviors\CreatedUpdatedBehavior;
use common\behaviors\FilesBehavior;
use common\behaviors\StatusBehavior;
use common\behaviors\ViewsBehavior;
use common\traits\CommonAttrsTrait;
use common\traits\SaveWithExceptionTrait;
use common\traits\SocAttributes;
use common\traits\Urls;

/**
 * Class PostArticle – Модель Публикации
 *
 * This is the model class for table "{{%post_article}}".
 *
 * @property int $lang_id
 * @property string $name
 * @property string $slug
 * @property string $announce
 * @property string $content
 * @property integer $main_image_file_id
 * @property string $main_image_url
 * @property integer $publish_at
 * @property integer $position
 * @property integer $views
 * @property boolean $visible
 * @property integer $lang_ru_id
 * @property integer $lang_kz_id
 * @property integer $lang_en_id
 *
 * @property string|null $thumbUrl
 * @property string|null $mainImageUrl
 *
 * @mixin Backup
 * @mixin FilesBehavior
 * @mixin PositionSortable
 * @mixin ViewsBehavior
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostArticle extends ActiveRecord
{
    use CommonAttrsTrait,
        SaveWithExceptionTrait,
        SocAttributes,
        Urls;

    /**
     * @inheritdoc
     * @return PostArticleQuery
     */
    public static function find()
    {
        return new PostArticleQuery(get_called_class());
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
            [
                'class' => FilesBehavior::class,
                'mainImageFieldName' => 'main_image_file_id',
            ],
            PositionSortable::class,
            StatusBehavior::class,
            ViewsBehavior::class,
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->createFrontendUrl(['posts/view', $this]);
    }

    /**
     * @return bool – опубликован?
     */
    public function isPublished()
    {
        return $this->publish_at < time();
    }

    //
    // Relations:
    //
    /**
     * @param int $size
     * @return string|null
     */
    public function getThumbUrl($size = 300)
    {
        $mainImage = $this->mainImage;

        if ($mainImage && ($url = $mainImage->getPreviewUrl($size))) {
            return $url;
        }

        return ($_ = $this->main_image_url) ? $_ : null;
    }

    /**
     * @return null|string
     */
    public function getMainImageUrl()
    {
        $mainImage = $this->mainImage;

        if ($mainImage && ($url = $mainImage->getUrl())) {
            return $url;
        }

        return ($_ = $this->main_image_url) ? $_ : null;
    }

    /**
     * @return PostArticle[]
     */
    public function findAllTranslations()
    {
        $ids = array_filter([
            $this->lang_ru_id,
            $this->lang_kz_id,
            $this->lang_en_id,
        ], function ($el) {
            return $el !== null;
        });

        if (!$ids) {
            return [];
        }

        $query = self::find();
        $query->andWhere(['id' => $ids]);
        $query->limit(count($ids));
        $query->indexBy('lang_id');

        return $query->all();
    }

    /**
     * @var PostArticle[]|null
     */
    protected $translations;

    /**
     * @return PostArticle[]
     */
    public function getTranslations()
    {
        return ($t = $this->translations) === null ? ($this->translations = $this->findAllTranslations()) : $t;
    }

    /**
     * @return array
     */
    public function getTranslationMap(): array
    {
        $return = $this->findAllTranslations();
        $return[$this->lang_id] = $this;
        return $return;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function commonSetUpdatedAttributes()
    {
        foreach ($this->getTranslations() as $translation) {
            $translation->setUpdatedAttributes();
        }

        $this->setUpdatedAttributes();
    }

    /**
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function commonDelete()
    {
        foreach ($this->getTranslations() as $translation) {
            $translation->refresh();
            $translation->delete();
        }
        $this->refresh();
        return $this->delete();
    }

    /**
     *
     */
    public function commonSave()
    {
        foreach ($this->getTranslations() as $translation) {
            $translation->save();
        }

        $this->save();
    }

    /**
     * @throws \Throwable
     */
    public function commonMoveAsFirst()
    {
        foreach ($this->getTranslations() as $translation) {
            $translation->refresh();
            $translation->moveAsFirst();
        }
        $this->refresh();
        return $this->moveAsFirst();
    }

    /**
     * @param self $prevModel
     * @return mixed
     * @throws \Throwable
     */
    public function commonMoveAfter(self $prevModel)
    {
        $prevModelTranslations = $prevModel->getTranslations();
        $prevModelTranslations[$prevModel->lang_id] = $prevModel;
        $prevModelTranslations = array_column($prevModelTranslations, null, 'position');

        $prevModel = $prevModelTranslations[min(array_keys($prevModelTranslations))];

        foreach ($this->getTranslations() as $translation) {
            $translation->refresh();
            $translation->moveAfter($prevModel);
        }

        $this->refresh();
        return $this->moveAfter($prevModel);
    }
}
