<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\behaviors\StatusBehavior;
use common\traits\SaveWithExceptionTrait;

/**
 * Class File – Модель Файла на сервере
 *
 * This is the model class for table "{{%file}}".
 *
 * @property integer $id
 * @property string $filename
 * @property integer $size
 * @property string $mime_type
 * @property string $original_filename
 * @property integer $owner_class_id
 * @property integer $owner_instance_id
 * @property integer $group
 * @property integer $position
 * @property integer $status
 * @property integer $status_at
 * @property integer $status_by
 * @property integer $added_at
 * @property integer $added_by
 *
 * @property string $url
 * @property string $fullUrl
 * @property string $path
 * @property string previewUrl
 * @property string previewFullUrl
 *
 * @mixin StatusBehavior
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class File extends ActiveRecord
{
    use SaveWithExceptionTrait;

    /**
     * @inheritdoc
     * @return FileQuery
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }

    /**
     * Создаем экземпляр файла с загруженного файла
     * @param ActiveRecord $owner
     * @param UploadedFile $uploadedFile
     * @param int $group
     * @return static
     */
    public static function createByUploadedFile(ActiveRecord $owner, UploadedFile $uploadedFile, $group = null)
    {
        $file = new static();
        $file->owner($owner);
        $file->filePath($uploadedFile->tempName, $uploadedFile->type);
        $file->original_filename = $uploadedFile->name;
        $file->group = $group;
        $file->setAddedAttributes();
        $file->changeStatusToActive();
        $file->saveWithException();

        // переносим файл
        try {
            $filePath = $file->getPath();
            FileHelper::createDirectory(dirname($filePath));
            if (!$uploadedFile->saveAs($filePath)) {
                throw new Exception(sprintf("Failed to save upload file. Remove file model with id `%s`", $file->id));
            }
        } catch (\Exception $e) {
            $file->delete();
        }

        return $file;
    }

    /**
     * Создаем экземпляр файла с файла на диске
     * @param ActiveRecord $owner
     * @param string $fileSource
     * @param null|int $group
     * @return static
     */
    public static function createByFile(ActiveRecord $owner, $fileSource, $group = null)
    {
        $file = new static();
        $file->owner($owner);
        $file->filePath($fileSource);
        $file->original_filename = basename($fileSource);
        $file->group = $group;
        $file->setAddedAttributes();
        $file->changeStatusToActive();
        $file->saveWithException();

        // переносим файл
        try {
            $filePath = $file->getPath();
            FileHelper::createDirectory(dirname($filePath));
            if (!copy($fileSource, $filePath)) {
                throw new Exception(sprintf("Failed to copy file. Remove file model with id `%s`", $file->id));
            }
        } catch (\Exception $e) {
            $file->delete();
        }

        return $file;
    }

    /**
     * Вернет id-класса по экземпляру класса
     *
     * @param ActiveRecord $owner
     * @return int
     * @throws Exception
     */
    public static function getOwnerClassIdByOwner(ActiveRecord $owner)
    {
        /**
         * @var $fileStorage \common\components\FileStorage
         */
        $fileStorage = Yii::$app->fileStorage;

        return $fileStorage->getOwnerClassIdByOwnerInstance($owner);
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var string – папка файлов
     */
    protected $folder = '/files';

    /**
     * Укажет поля добавления файла
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public function setAddedAttributes()
    {
        $this->added_at = time();
        $this->added_by = ($user = Yii::$app->get('user', false)) && !$user->isGuest ? $user->id : null;

        return $this;
    }

    /**
     * Укажет поля владельца
     * @param ActiveRecord $owner
     * @return $this
     * @throws Exception
     */
    public function owner(ActiveRecord $owner)
    {
        $this->owner_class_id = static::getOwnerClassIdByOwner($owner);
        $this->owner_instance_id = $owner->id;

        return $this;
    }

    /**
     * Укажет инфо поля по файлу
     * @param $filePath
     * @param string|null $currentMimeType
     * @return $this
     * @throws Exception
     */
    public function filePath($filePath, string $currentMimeType = null)
    {
        $mimeType = FileHelper::getMimeType($filePath);
        $extensions = FileHelper::getExtensionsByMimeType($mimeType);

        if (!$extensions) {
            // костыль, не знаем ничего про 'application/vnd.ms-office'
            if ($mimeType == 'application/vnd.ms-office' && in_array($currentMimeType, ['application/vnd.ms-excel'])) {
                $mimeType = $currentMimeType;
                $extensions = FileHelper::getExtensionsByMimeType($mimeType);
            }
        }

        if (count($extensions) == 1) {
            $extension = current($extensions);
        } else {
            $popExtensions = [
                'txt',
                'doc',
                'html',
                'jpg',
                'mpeg',
                'mp3',
                'mkv',
                'mov',
                'mp4',
                'ogg',
                'ppt',
                'xhtml',
                'xls',
                'xml',
            ];

            $_extensions = array_intersect($extensions, $popExtensions);
            if (!$_extensions) {
                throw new Exception(
                    sprintf('Extension for file `%s` by MIME-type `%s` not found. Extensions: %s',
                        basename($filePath),
                        $mimeType,
                        json_encode($extensions))
                );
            }

            $extension = current($_extensions);
        }

        $this->filename = \Yii::$app->security->generateRandomString(rand(5, 10)) . '.' . $extension;
        $this->size = filesize($filePath);
        $this->mime_type = $mimeType;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            StatusBehavior::class,
        ];
    }

    /**
     * Вернет URL до файла
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->folder . $this->getFilePath();
    }

    /**
     * Вернет полный путь до файла
     *
     * @return string
     */
    public function getFullUrl()
    {
        return Yii::getAlias('@frontendWeb') . $this->getUrl();
    }

    /**
     * Вернет путь до файла на диске
     *
     * @return string
     */
    public function getPath()
    {
        return Yii::getAlias('@frontendWebroot') . $this->folder . $this->getFilePath();
    }

    /**
     * Вернет путь до файла
     * - только структура, внешне должны указать где на диске хранится и откуда URL строится
     * - /000/000/969fbd006.jpg
     *
     * @return string
     */
    protected function getFilePath()
    {
        $id = $this->id;
        $parts = str_split((string)sprintf('%09d', $id), 3);
        array_pop($parts);

        return '/'
            . implode('/', $parts)
            . '/'
            . pathinfo($this->filename, PATHINFO_FILENAME)
            . sprintf('%03d', substr($id, -3))
            . '.'
            . pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $return = parent::afterDelete();

        $this->deleteFile();

        return $return;
    }

    /**
     * Удалит файл
     *
     * @return $this
     */
    public function deleteFile()
    {
        $file = $this->getPath();

        if (file_exists($file)) {
            unlink($file);
        }

        // todo: find . -type d -empty -delete

        return $this;
    }

    /**
     * Пока только 300x300px
     *
     * @todo: разные размеры!
     *
     * @param int $size
     * @return string
     */
    public function getPreviewUrl($size = 300)
    {
        $filePath = $this->getFilePath();

        $filename = pathinfo($filePath, PATHINFO_FILENAME);
        $filenameLen = strlen($filename);

        $newFilename = substr($filename, $filenameLen - 3) . '-' . substr($filename, 0, $filenameLen - 3);

        return '/resized'
            . pathinfo($filePath, PATHINFO_DIRNAME)
            . '/'
            . $newFilename
            . '/' . $this->id . '--' . $size . '.jpg';
    }

    /**
     * Вернет полный путь до превью
     *
     * @return string
     */
    public function getPreviewFullUrl()
    {
        return Yii::getAlias('@frontendWeb') . $this->getPreviewUrl();
    }

    /**
     * Изменяет размер изображения
     * @param $width
     * @param $height
     * @param int $jpeg_quality
     * @return $this
     */
    public function resize($width, $height, $jpeg_quality = 80)
    {
        $path = $this->path;
        Image::resize($path, $width, $height)->save($path, ['jpeg_quality' => $jpeg_quality]);
        $this->size = filesize($path);
        return $this;
    }

    /**
     * Оптимизация изображения
     * @return bool
     * @throws Exception
     */
    public function imageOptimize()
    {
        return $this
            ->resize(2048, 2048, 80)
            ->saveWithException(true, ['size']);
    }
}
