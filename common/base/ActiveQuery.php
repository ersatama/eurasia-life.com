<?php

namespace common\base;

use yii\db\ActiveQuery as BaseActiveQuery;

/**
 * Class ActiveQuery – Расширяем возможности
 * - добавляем поиск по первичному ключу
 * - сортировка по-умолчанию
 *
 * @see \yii\db\ActiveRecord
 *
 * @package common\base
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ActiveQuery extends BaseActiveQuery
{
    /**
     * Добавит условие id = $id
     * @param $id
     * @return $this
     */
    public function id($id)
    {
        return $this->andWhere(['id' => $id]);
    }

    /**
     * Добавит сортировку по-умолчанию
     * @return \yii\db\ActiveQuery
     */
    public function sort()
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }

    /**
     * Вернет полное название поля %{tableName}.[[columnName]]
     * @param $columnName
     * @return string
     */
    public function getFullColumnName($columnName)
    {
        /**
         * @var $modelClass \yii\db\ActiveRecord
         */
        $modelClass = $this->modelClass;
        $tableName = $modelClass::tableName();
        return sprintf('%s.[[%s]]', $tableName, $columnName);
    }
}
