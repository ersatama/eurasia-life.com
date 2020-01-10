<?php

namespace common\behaviors;

use yii\base\Exception;
use yii\web\UploadedFile;
use common\models\File;

/**
 * Interface FilesBehaviorInterface – Поведение для привязки файлов к моделям
 * @package common\behaviors
 */
interface FilesBehaviorInterface
{
    /**
     * Связь с главной фоткой, через связь с файлами
     * @return \common\models\FileQuery|\yii\db\ActiveQuery
     * @throws Exception
     */
    public function getMainImage();

    /**
     * Связывает загруженную главную картинку с записью
     * @param UploadedFile|null $uploadedFile
     * @return File|null
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function linkUploadedMainImage(UploadedFile $uploadedFile = null);

    /**
     * Связывает загруженный файл с записью
     * @param UploadedFile|null $uploadedFile
     * @param $fieldName
     * @return File|null
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function linkUploadedFile(UploadedFile $uploadedFile, $fieldName);

    /**
     * Удаляем связанный файл
     * @param $fieldName
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function unlinkFileByFieldName($fieldName);

    /**
     * Удаляем главную картику
     * @return void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function unlinkMainImage();

    /**
     * Связь с файлами
     * warning: если что меняем тут, то следим как отреагирует getMainImage()
     * @return \common\models\FileQuery|\yii\db\ActiveQuery
     */
    public function getFiles();

    /**
     * Добавляет загруженный файл
     * @param UploadedFile $uploadedFile
     * @param int $group
     * @return File
     */
    public function addUploadedFile(UploadedFile $uploadedFile, $group = null);

    /**
     * Добавляет файл с диска
     * @param string $filePath
     * @param null|int $group
     * @return File
     */
    public function addFile($filePath, $group = null);

    /**
     * Обновит позиции файлов
     * - нужно передать массив id в нужном порядке [3, 1, 2]
     * @param array $ids
     * @throws \Throwable
     */
    public function updateFilePositions(array $ids);

    /**
     * Вернет первый файл
     * @return \common\models\FileQuery|\yii\db\ActiveQuery
     */
    public function getFirstFile();
}
