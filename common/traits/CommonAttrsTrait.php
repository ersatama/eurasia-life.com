<?php

namespace common\traits;

/**
 * Trait CommonAttrsTrait — трейт с общими атрибутами моделей
 *
 * @mixin \common\behaviors\CreatedUpdatedBehavior
 * @mixin \common\behaviors\StatusBehavior
 *
 * @property integer $id
 * @property integer $status
 * @property integer $status_at
 * @property integer $status_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $created_by
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait CommonAttrsTrait
{

}
