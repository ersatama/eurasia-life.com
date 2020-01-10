<?php

namespace backend\actions;

use yii\base\Action;
use common\helpers\Html;

/**
 * Class GetSlug – Действие для контроллера: отдает slug на строку
 *
 * @package backend\actions
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class GetSlug extends Action
{
    /**
     * Запускаем действие
     *
     * @param $string
     * @return \yii\web\Response
     */
    public function run($string)
    {
        return $this->controller->asJson([
            'slug' => Html::slug($string),
        ]);
    }
}
