<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\behaviors\CreatedUpdatedBehavior;
use common\behaviors\FilesBehavior;
use common\behaviors\StatusBehavior;
use common\traits\SaveWithExceptionTrait;

/**
 * Class ShortCode – Модель Шорткода
 *
 * This is the model class for table "{{%short_code}}".
 *
 * @property integer $id
 * @property string $for
 * @property string $short_code
 * @property string $content
 * @property string $type
 * @property string $label
 * @property string $hint
 * @property string $placeholder
 *
 * @mixin CreatedUpdatedBehavior
 * @mixin FilesBehavior
 * @mixin StatusBehavior
 * @mixin SaveWithExceptionTrait
 *
 * @property array $sortFiles
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCode extends ActiveRecord
{
    use SaveWithExceptionTrait;

    const TYPE_TEXTAREA = 'textarea';

    const TYPE_INPUT = 'input';

    const TYPE_BOOLEAN = 'boolean';

    const TYPE_REDACTOR = 'redactor';

    const TYPE_GALLERY = 'gallery';

    const TYPE_IMAGE = 'image';

    /**
     * @inheritdoc
     * @return ShortCodeQuery
     */
    public static function find()
    {
        return new ShortCodeQuery(get_called_class());
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            CreatedUpdatedBehavior::class,
            FilesBehavior::class,
            StatusBehavior::class,
        ];
    }

    /**
     * Установит контент как объект/массив
     * @param $content
     */
    public function setObjectContent($content)
    {
        $this->content = json_encode($content);
    }

    /**
     * Вернет контент как объект/массив
     * @return mixed
     */
    public function getObjectContent()
    {
        return json_decode($this->content);
    }

    /**
     * Вернет отсортированные поля
     * @return array|File[]
     */
    public function getSortFiles()
    {
        $files = $this->files;

        $sort = (array)$this->getObjectContent();

        if ($sort) {
            $_files = [];
            foreach ($files as $file) {
                $_files[$file->id] = $file;
            }

            $files = [];
            foreach ($sort as $key => $value) {
                if (isset($_files[$key])) {
                    $files[] = $_files[$key];
                    unset($_files[$key]);
                }
            }

            $files = array_merge($files, array_values($_files));
        }

        return $files;
    }
}
