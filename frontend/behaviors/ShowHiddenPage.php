<?php

namespace frontend\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class ShowHiddenPage – подскажет можем показывать скрытую страницу или нет
 *
 * @property yii\web\Controller|yii\base\Component $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShowHiddenPage extends Behavior
{
    /**
     * @var bool|null – показываем?
     */
    protected $showHiddenPage;

    /**
     * @return bool
     */
    public function showHiddenPage()
    {
        if ($this->showHiddenPage === null) {
            $this->showHiddenPage = isset(Yii::$app->params['show-hidden-page'])
                && Yii::$app->request->get(Yii::$app->params['show-hidden-page']) !== null;
        }

        return $this->showHiddenPage;
    }
}
