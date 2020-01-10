<?php

namespace backend\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use common\models\File;

/**
 * Class RedactorUploadFileForm – Форма загрузки файлов с редактора
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class RedactorUploadFileForm extends Model
{
    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var ActiveRecord|\common\behaviors\FilesBehaviorInterface
     */
    public $model;

    /**
     * @var UploadedFile[]
     */
    public $uploadedFiles;

    /**
     * @var File[]
     */
    protected $files = [];

    /**
     * RedactorUploadFileForm constructor.
     * @param ActiveRecord $model
     * @param array $config
     */
    public function __construct(ActiveRecord $model, array $config = [])
    {
        parent::__construct($config);

        $this->model = $model;

        $this->uploadedFiles = UploadedFile::getInstances($this, 'file');
    }

    /**
     * Загрузка файла – Основной метод
     *
     * @return bool
     */
    public function upload()
    {
        if (!$this->validate()) {
            return false;
        }

        foreach ($this->uploadedFiles as $uploadedFile) {
            $file = $this->model->addUploadedFile($uploadedFile);
            if ($file) {
                $this->files[] = $file;
            }
        }

        return count($this->files) > 0;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uploadedFiles', 'required'],
            ['uploadedFiles', 'file', 'maxFiles' => 10],
        ];
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }
}
