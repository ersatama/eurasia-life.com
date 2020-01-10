<?php

namespace common\models;

use common\base\ActiveQuery;
use common\behaviors\StatusQueryBehavior;

/**
 * Class ShortCodeQuery – Выборка Шорткодов
 *
 * @mixin StatusQueryBehavior
 *
 * @method ShortCode|\yii\db\ActiveRecord|array|null one($db = null)
 * @method ShortCode[]|array|\yii\db\ActiveRecord[] all($db = null)
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCodeQuery extends ActiveQuery
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
}
