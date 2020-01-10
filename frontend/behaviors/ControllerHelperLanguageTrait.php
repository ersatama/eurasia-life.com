<?php

namespace frontend\behaviors;

use common\models\Language;

/**
 * Trait ControllerHelperLanguageTrait — Трейт помогает работать с языками
 *
 * @property \yii\web\Controller $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait ControllerHelperLanguageTrait
{
    /**
     * Перед показом вью загрузим все посадочные страницы
     */
    protected function languageBeforeViewRender()
    {
        $this->owner->getView()->params['languages'] = $this->getAllLanguages();
    }

    /**
     * @return Language[]
     */
    public function getAllLanguages()
    {
        return Language::getAllWithCache();
    }
}
