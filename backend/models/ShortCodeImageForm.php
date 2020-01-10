<?php

namespace backend\models;

use yii\web\UploadedFile;
use common\behaviors\NotifyBehavior;

/**
 * Class ShortCodeImageForm – Форма Картинки Шорткода
 *
 * @mixin NotifyBehavior
 *
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCodeImageForm extends ShortCodeForm
{
    /**
     * @var UploadedFile
     */
    public $image;

    /**
     * @var int|null
     */
    public $removeImage;

    /**
     * @var boolean
     */
    public $uploadBtn;

    /**
     * @inheritdoc
     * - если есть новое изображение
     * - если попросили удалить
     * - если нажали на кнопку Загрузки
     */
    public function load($data, $formName = null)
    {
        $return = parent::load($data, $formName);

        $this->image = UploadedFile::getInstance($this, 'image');

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

        if ($this->image) {
            $this->removeCurrentFiles();

            $this->shortCode->addUploadedFile($this->image);
            $this->notifySuccess('Изображение успешно загружено.');
        }

        if ($this->removeImage) {
            $this->removeCurrentFiles();
            $this->notifySuccess('Изображение успешно удалено.');
        }

        return parent::save(false);
    }

    /**
     * Удаляем файлы
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function removeCurrentFiles()
    {
        foreach ($this->shortCode->files as $file) {
            $file->delete();
        }
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
                'image',
                'image',
                'mimeTypes' => [
                    'image/png',
                    'image/jpg',
                    'image/jpeg',
                    'image/gif',
                ],
                'extensions' => 'png, jpg, jpeg, gif',
            ],
            [
                'image',
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
    var submit = attribute.\$form.data('yiiActiveForm').submitObject;
    return submit && submit.attr('name') == '$uploadBtnName';
}
JS;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'image' => 'Изображение',
        ]);
    }
}
