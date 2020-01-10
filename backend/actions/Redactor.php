<?php

namespace backend\actions;

use yii\base\Action;
use yii\web\NotFoundHttpException;
use common\helpers\Html;
use common\models\File;
use backend\models\RedactorUploadFileForm;
use backend\models\RedactorUploadImageForm;

/**
 * Class Redactor – Список файлов/картинок для редактора
 * @package backend\actions
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Redactor extends Action
{
    // работаем с файлам
    const MODE_FILES = 1 << 1;

    // работаем с картинками
    const MODE_IMAGES = 1 << 2;

    // разрешаем загрузку
    const MODE_UPLOAD = 1 << 3;

    // загрузка файлов
    const MODE_UPLOAD_FILES = self::MODE_FILES | self::MODE_UPLOAD;

    // загрузка картинок
    const MODE_UPLOAD_IMAGES = self::MODE_IMAGES | self::MODE_UPLOAD;

    /**
     * @var int – Режим работы
     */
    public $mode;

    /**
     * @var string|callable – Как найти модель
     */
    public $findOneById = 'findOneById';

    /**
     * Запускаем действие
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function run($id)
    {
        return $this->mode & self::MODE_UPLOAD ? $this->runUpload($id) : $this->runList($id);
    }

    /**
     * Список файлов/картинок
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    protected function runList($id)
    {
        $model = $this->findOneById($id);

        $fileQuery = $model->getFiles();
        $fileQuery->active();
        $fileQuery->sort();

        $resultMethod = 'result';
        if ($this->mode & self::MODE_IMAGES) {
            $fileQuery->image();
            $resultMethod = 'resultImage';
        }

        return $this->controller->asJson(call_user_func([$this, $resultMethod], $fileQuery->all()));
    }

    /**
     * Загрузка файлов/картинок
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    protected function runUpload($id)
    {
        $model = $this->findOneById($id);

        if ($this->mode & self::MODE_IMAGES) {
            $uploadForm = new RedactorUploadImageForm($model);
            $resultMethod = 'resultImage';
        } else {
            $uploadForm = new RedactorUploadFileForm($model);
            $resultMethod = 'result';
        }

        $result = $uploadForm->upload() && ($files = $uploadForm->getFiles()) ? $this->$resultMethod($files) : [
            'error' => true,
            'message' => $uploadForm->getFirstError('file'),
        ];

        return $this->controller->asJson($result);
    }

    /**
     * Вернет информацию о файле для редактора
     * @param File[] $files
     * @return array
     */
    protected function result($files)
    {
        $return = [];

        $i = 0;
        foreach ($files as $file) {
            $title = Html::encode($file->original_filename);
            $return['file-' . $i++] = [
                'id' => $file->id,
                'title' => $title,
                'name' => $title,
                'url' => $file->fullUrl,
                'size' => $file->size,
            ];
        }

        return $return;
    }

    /**
     * Вернет информацию о картинке для редактора
     * @param File[] $imageFiles
     * @return array
     */
    protected function resultImage($imageFiles)
    {
        $return = [];

        $i = 0;
        foreach ($imageFiles as $imageFile) {
            $id = $imageFile->id;
            $url = $imageFile->fullUrl;
            $return['file-' . $i++] = [
                'thumb' => $url,
                'url' => $url,
                'title' => $id,
                'id' => $id,
            ];
        }

        return $return;
    }

    /**
     * Вернет модель с которой работаем
     * @param $id
     * @return \yii\db\ActiveRecord|\common\behaviors\FilesBehaviorInterface
     * @throws NotFoundHttpException
     */
    protected function findOneById($id)
    {
        $findOneById = $this->findOneById;

        $model = null;

        if (is_string($findOneById)) {
            $model = $this->controller->$findOneById($id);
        }

        if (is_callable($findOneById)) {
            $model = $findOneById($id);
        }

        if (!$model) {
            throw new NotFoundHttpException(sprintf('Model with id `%s` not found', $id));
        }

        return $model;
    }
}