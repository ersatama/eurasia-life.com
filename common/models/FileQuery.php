<?php

namespace common\models;

use yii\db\Expression;
use common\base\ActiveQuery;
use common\behaviors\StatusQueryBehavior;

/**
 * Class FileQuery – Выборка Файлов
 *
 * @mixin StatusQueryBehavior
 *
 * @method File|array|null|\yii\db\ActiveRecord one($db = null)
 * @method File[]|array|\yii\db\ActiveRecord[] all($db = null)
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FileQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            StatusQueryBehavior::class,
        ];
    }

    /**
     * Добавит условие mime_type == image
     * @return $this
     */
    public function image()
    {
        return $this->andWhere(['in', 'mime_type', [
            'image/png',
            'image/jpg',
            'image/jpeg',
            'image/gif',
        ]]);
    }

    /**
     * Добавит сортировку по-умолчанию
     * @return $this
     */
    public function sort()
    {
        return $this->orderBy([
            new Expression('position > 0 desc'),
            'position' => 'asc',
            'id' => 'asc',
        ]);
    }

    /**
     * Добавит условие group == $group
     * @param $group
     * @return $this
     */
    public function group($group)
    {
        return $this->andWhere(['group' => $group]);
    }

    /**
     * Переключить на связь один-к-одному по полю
     * @param $fieldName
     * @return $this
     */
    public function toHasOneByField($fieldName)
    {
        $this->multiple = false;
        $this->link['id'] = $fieldName;
        return $this;
    }
}

