<?php

namespace frontend\widgets;

use yii\base\Widget;
use frontend\assets\ShareAsset;

/**
 * Class Share – Виджет показывает блок «Поделиться» от
 * - Яндекс
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Share extends Widget
{
    /**
     * @var string – Обвернет через sprintf
     */
    public $block;

    /**
     * @var bool — белые кнопки
     */
    public $isLight = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        ShareAsset::register($this->view);

        $isLight = '';
        if($this->isLight === true) {
            $isLight = ' likely-light';
        }

        $content = <<<TEXT
<div class="likely{$isLight}">
    <div class="facebook">Поделиться</div>
    <div class="vkontakte">Поделиться</div>
    <div class="twitter">Твитнуть</div>
    <div class="odnoklassniki">Класснуть</div>
    <div class="telegram"></div>
    <div class="whatsapp"></div>
</div>
TEXT;
        return $this->block ? sprintf($this->block, $content) : $content;
    }
}
