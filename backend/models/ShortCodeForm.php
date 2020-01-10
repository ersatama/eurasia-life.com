<?php

namespace backend\models;

use yii\base\Model;
use common\behaviors\NotifyBehavior;
use common\models\ShortCode;

/**
 * Class ShortCodeForm – Форма Шорткода
 *
 * @mixin NotifyBehavior
 *
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCodeForm extends Model
{
    // Главная кнопка, если нажали ее то запускаем сохранение всех форм
    const BUTTON_MAIN_SAVE = 'save';

    /**
     * Создаем форму для шорткода
     *
     * @param ShortCode $shortCode
     * @return ShortCodeForm|ShortCodeGalleryForm
     */
    public static function createForm(ShortCode $shortCode)
    {
        if ($shortCode->type == ShortCode::TYPE_GALLERY) {
            return new ShortCodeGalleryForm($shortCode);
        }

        if ($shortCode->type == ShortCode::TYPE_IMAGE) {
            return new ShortCodeImageForm($shortCode);
        }

        return new ShortCodeForm($shortCode);
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var string
     */
    public $content;

    /**
     * @var ShortCode
     */
    public $shortCode;

    /**
     * @inheritdoc
     * @param ShortCode $shortCode
     * @param array $config
     */
    public function __construct(ShortCode $shortCode, array $config = [])
    {
        $this->shortCode = $shortCode;
        $this->setAttributes($shortCode->getAttributes([
            'content',
        ]), false);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NotifyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }

        $this->shortCode->setAttributes($this->getAttributes([
            'content',
        ]), false);

        $this->shortCode->setUpdatedAttributes();
        $this->shortCode->saveWithException();

        return true;
    }

    /**
     * @return ShortCode
     */
    public function getShortCode()
    {
        return $this->shortCode;
    }

    /**
     * Вернет true если тек. форма активна - нажали кнопку
     * @return bool
     */
    public function activeByBtn()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['content', 'trim'],
            ['content', 'string', 'max' => 65000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'content' => 'Контент',
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'short-code-' . $this->shortCode->id;
    }
}
