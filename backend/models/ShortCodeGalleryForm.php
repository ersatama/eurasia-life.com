<?php

namespace backend\models;

use yii\web\UploadedFile;
use common\behaviors\NotifyBehavior;

/**
 * Class ShortCodeGalleryForm – Форма Галереи Шорткода
 *
 * @mixin NotifyBehavior
 *
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCodeGalleryForm extends ShortCodeForm
{
    /**
     * @var UploadedFile|null
     */
    public $images;

    /**
     * @var int|null
     */
    public $removeImage;

    /**
     * @var boolean
     */
    public $uploadBtn;

    /**
     * @var array
     */
    public $sort;

    /**
     * @inheritdoc
     * - если есть новое изображение
     * - если попросили удалить
     * - если нажали на кнопку Загрузки
     */
    public function load($data, $formName = null)
    {
        $return = parent::load($data, $formName);

        $this->images = UploadedFile::getInstances($this, 'images');

        return $return;
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }

        if ($this->images) {
            foreach ($this->images as $image) {
                $this->shortCode->addUploadedFile($image);
            }
            $this->notifySuccess('Изображение успешно загружено.');
        }

        $content = $this->sort;

        if (!is_array($content)) {
            $content = [];
        }

        if ($this->removeImage) {
            foreach ($this->shortCode->files as $file) {
                if ($file->id == $this->removeImage) {
                    $file->delete();
                    break;
                }
            }
            if (isset($content[$this->removeImage])) {
                unset($content[$this->removeImage]);
            }

            $this->notifySuccess('Изображение успешно удалено.');
        }

        $this->content = json_encode($content);

        return parent::save(false);
    }

    /**
     * Вернут true если загружают или удаляют картинку
     * @return bool
     */
    public function activeByBtn()
    {
        return (bool)$this->removeImage || (bool)$this->uploadBtn;
    }

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                'images',
                'file',
                'extensions' => 'png, jpg, gif',
                'maxFiles' => 10,
            ],
            [
                'images',
                'required',
                'when' => function (self $model, $attribute) {
                    return $model->uploadBtn;
                },
                'whenClient' => $this->getImageWhenClient(),
            ],
            [
                'removeImage',
                'integer',
            ],
            [
                'uploadBtn',
                'boolean',
            ],
            [
                'sort',
                'safe'
            ],
        ]);
    }

    /**
     * Вернет скрипт для валидации картинки
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getImageWhenClient()
    {
        $uploadBtnName = sprintf('%s[%s]', $this->formName(), 'uploadBtn');

        return <<<JS
function (attribute, value) {
    return attribute.\$form.data('yiiActiveForm').submitObject.attr('name') == '$uploadBtnName';
}
JS;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'images' => 'Изображения',
        ]);
    }
}
