<?php

namespace backend\models;

use common\models\File;

/**
 * Class RedactorUploadImageForm – Форма загрузки изображений с редактора
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class RedactorUploadImageForm extends RedactorUploadFileForm
{
    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uploadedFiles', 'required'],
            ['uploadedFiles', 'image', 'maxFiles' => 10, 'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']]
        ];
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function upload()
    {
        $return = parent::upload();

        if ($return) {
            array_map(function (File $file) {
                $file->imageOptimize();
            }, $this->files);
        }

        return $return;
    }
}
