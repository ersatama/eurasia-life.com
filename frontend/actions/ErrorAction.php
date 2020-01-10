<?php

namespace frontend\actions;

use common\models\Language;

/**
 * Class ErrorAction — Переназначил, чтоб добавлять контент на страницы ошибок
 *
 * @package frontend\actions
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * @inheritdoc
     */
    protected function getViewRenderParams()
    {
        $this->controller->view->params['languages-urls'] = array_map(function (Language $language) {
            return ['url' => ['site/index', 'language' => $language->slug]];
        }, array_column(Language::getAll(), null, 'id'));

        return array_merge(parent::getViewRenderParams(), [
        ]);
    }
}
