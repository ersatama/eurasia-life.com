<?php

namespace common\traits;

/**
 * Trait SocAttributes – Трейт добавляет соц поля для моделей
 *
 * @property string $soc_title
 * @property string $soc_content
 * @property integer $soc_image_id
 * @property \common\models\File $socImage
 *
 * @package common\traits
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait SocAttributes
{
    /**
     * Связь с картинкой для соц сети
     * @return \common\models\FileQuery|\yii\db\ActiveQuery
     */
    public function getSocImage()
    {
        $query = $this->getFiles();
        $query->multiple = false;
        $query->link['id'] = 'soc_image_id';
        return $query;
    }
}
