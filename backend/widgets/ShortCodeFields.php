<?php

namespace backend\widgets;

use yii\base\Widget;

/**
 * Class ShortCodeFields – Виджет помогает показывать поля шорткодов
 *
 * @package backend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCodeFields extends Widget
{
    /**
     * @var \common\widgets\ActiveForm
     */
    public $form;

    /**
     * @var \backend\models\ShortCodeForm
     */
    public $shortCodeForm;

    /**
     * @var callable
     */
    public $renderCallback;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('short-code-fields', [
            'form' => $this->form,
            'shortCodeForm' => $this->shortCodeForm,
            'renderCallback' => $this->renderCallback,
        ]);
    }
}
