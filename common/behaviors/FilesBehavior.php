<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;
use common\models\File;

/**
 * Class FilesBehavior – Поведение для AR-моделей, помогает работать с файлами
 *
 * @property ActiveRecord $owner
 * @property File $mainImage
 * @property File[] $files
 * @property File $firstFile
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FilesBehavior extends Behavior implements FilesBehaviorInterface
{
    /**
     * @var string – если у владельца(модель) есть главная фотка, то в этом поле храним ID-файла
     */
    public $mainImageFieldName;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete', // @todo: before? внешие ключи?
        ];
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterDelete()
    {
        foreach ($this->getFiles()->all() as $file) {
            $file->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function getMainImage()
    {
        if (!$this->mainImageFieldName) {
            throw new Exception('The property `mainImageFieldName` can\'t be empty.');
        }

        $query = $this->getFiles();
        $query->multiple = false;
        $query->link['id'] = $this->mainImageFieldName;
        return $query;
    }

    /**
     * @inheritdoc
     */
    public function linkUploadedMainImage(UploadedFile $uploadedFile = null)
    {
        return $this->linkUploadedFile($uploadedFile, $this->mainImageFieldName);
    }

    /**
     * @inheritdoc
     */
    public function linkUploadedFile(UploadedFile $uploadedFile, $fieldName)
    {
        if (!$uploadedFile) {
            return null;
        }

        $file = $this->addUploadedFile($uploadedFile);

        if ($file) {
            $this->unlinkFileByFieldName($fieldName);
            $this->owner->$fieldName = $file->id;
        }

        return $file;
    }

    /**
     * @inheritdoc
     */
    public function unlinkFileByFieldName($fieldName)
    {
        $query = $this->getFiles();
        $query->andWhere(['id' => $this->owner->$fieldName]);
        $query->limit(1);
        $file = $query->one();

        // @todo: транзакцию?
        if ($file) {
            $owner = $this->owner;
            $owner->$fieldName = null;
            $owner->save(false, [$fieldName]);
            $file->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function unlinkMainImage()
    {
        return $this->unlinkFileByFieldName($this->mainImageFieldName);
    }

    /**
     * @inheritdoc
     */
    public function getFiles()
    {
        return $this->owner->hasMany(File::class, ['owner_instance_id' => 'id'])
            ->andWhere(['owner_class_id' => File::getOwnerClassIdByOwner($this->owner)]);
    }

    /**
     * @inheritdoc
     */
    public function addUploadedFile(UploadedFile $uploadedFile, $group = null)
    {
        return File::createByUploadedFile($this->owner, $uploadedFile, $group);
    }

    /**
     * @inheritdoc
     */
    public function addFile($filePath, $group = null)
    {
        return File::createByFile($this->owner, $filePath, $group);
    }

    /**
     * @inheritdoc
     */
    public function updateFilePositions(array $ids)
    {
        if ($ids) {
            $idsString = implode(', ', $ids);

            File::getDb()->transaction(function () use ($ids, $idsString) {
                File::updateAll([
                    'position' => null
                ], ['id' => $ids]);

                File::updateAll([
                    'position' => new Expression("FIELD(`id`, $idsString)")
                ], ['id' => $ids]);
            });
        }
    }

    /**
     * @inheritdoc
     */
    public function getFirstFile()
    {
        $query = $this->getFiles();
        $query->multiple = false;
        $query->groupBy(['owner_class_id', 'owner_instance_id']);
        $query->sort();

        return $query;
    }
}
