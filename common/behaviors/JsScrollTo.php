<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\web\View;

/**
 * Class JsScrollTo – Поведение помогает проскроллить страницу
 *
 * @property \yii\web\Controller $owner
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class JsScrollTo extends Behavior
{
    /**
     * @var string
     */
    public $target;

    /**
     * @var int
     */
    public $time = 500;

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $this->owner->view->on(View::EVENT_END_BODY, [$this, 'viewEventEndBody']);
    }

    /**
     * @inheritdoc
     */
    public function detach()
    {
        $this->owner->view->off(View::EVENT_END_BODY, [$this, 'viewEventEndBody']);

        parent::detach();
    }

    /**
     * Добавляем в конец страницы скрипт прокрутки
     */
    public function viewEventEndBody()
    {
        if ($this->target) {
            if (($time = $this->time)) {
                $js = sprintf(
                    '$("body, html").animate({scrollTop: $("%s").offset().top - 170}, %s);',
                    $this->target,
                    $time
                );
            } else {
                $js = sprintf('$("body, html").scrollTop($("%s").offset().top);', $this->target);
            }
            $this->owner->view->registerJs($js);
        }
    }

    /**
     * @param $target
     * @param null|int $time
     */
    public function jsScrollTo($target, $time = null)
    {
        $this->target = $target;
        if ($time !== null) {
            $this->time = $time;
        }
    }
}
