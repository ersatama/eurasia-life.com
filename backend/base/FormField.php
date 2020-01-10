<?php

namespace backend\base;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\base\Component;

/**
 * Class FormField – Поле формы
 *
 * @package backend\base
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FormField extends Component
{
    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -/*/

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var bool
     */
    protected $typeInit = false;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected $dropDownList = [];

    /**
     * @var FormModel
     */
    protected $owner;

    /**
     * @var bool — тек. поле файл?
     */
    protected $isFileField = false;

    /**
     * @var null|string – названия поля для линковки файлов
     */
    protected $linkFieldName;

    /**
     * @var null|callable
     */
    protected $actionCallback;

    /**
     * @inheritdoc
     */
    public function __construct(FormModel $owner, array $config = [])
    {
        $this->owner = $owner;

        parent::__construct($config);
    }

    /**
     * Строка
     * @param int $max
     * @param array $rule
     * @return $this
     */
    public function string($max = 255, array $rule = [])
    {
        $this->typeInit = true;

        array_unshift($rule, __FUNCTION__);

        $rule['max'] = $max;

        return $this->rule($rule)->trim();
    }

    /**
     * Текст (более 255 символов)
     * @param int $max
     * @return $this
     */
    public function text($max = 65000)
    {
        return $this->string($max);
    }

    /**
     * Целое число
     * @param null|int $max
     * @param array $rule
     * @return $this
     */
    public function integer($max = null, array $rule = [])
    {
        $this->typeInit = true;

        array_unshift($rule, __FUNCTION__);

        if ($max !== null) {
            $rule['max'] = $max;
        }

        return $this->rule($rule);
    }

    /**
     * Булеан
     * @param array $rule
     * @return $this
     */
    public function boolean(array $rule = [])
    {
        $this->typeInit = true;

        array_unshift($rule, __FUNCTION__);

        return $this->rule($rule);
    }

    /**
     * Поле обязательное
     * @param array $rule
     * @return $this
     */
    public function required(array $rule = [])
    {
        array_unshift($rule, __FUNCTION__);

        return $this->rule($rule);
    }

    /**
     * Фильтр trim (убрать отступы слева и справа)
     * @param array $rule
     * @return $this
     */
    public function trim(array $rule = [])
    {
        array_unshift($rule, __FUNCTION__);

        return $this->rule($rule);
    }

    /**
     * Значение должно быть одно из...
     * @param array $range
     * @param array $rule
     * @return FormField
     */
    public function in(array $range, array $rule = [])
    {
        $rule['range'] = $range;

        array_unshift($rule, __FUNCTION__);

        return $this->rule($rule);
    }

    /**
     * Поле должно быть уникальным
     * @param null|string $targetClass
     * @param null|callable $filter
     * @param array $rule
     * @return $this
     */
    public function unique($targetClass = null, $filter = null, array $rule = [])
    {
        if ($targetClass === null) {
            $targetClass = get_class($this->owner->getModel());
        }

        if ($filter === null) {
            $filter = function (ActiveQuery $query) {
                $query->andWhere(['!=', 'id', $this->owner->getId()]);
                $query->limit(1);
            };
        }

        $rule['targetClass'] = $targetClass;
        $rule['filter'] = $filter;

        array_unshift($rule, __FUNCTION__);

        return $this->rule($rule);
    }

    /**
     * Выпадающий список (селект)
     * @param ActiveRecord|string $targetClass
     * @param null|callable $filter
     * @param string|callable $name
     * @return FormField
     */
    public function dropDownList($targetClass, $filter = null, $name = 'name')
    {
        $query = $targetClass::find();

        if ($filter) {
            $filter($query);
        }

        $dropDownList = [];
        foreach ($query->all() as $model) {
            $dropDownList[$model->id] = is_callable($name) ? $name($model) : $model->$name;
        }

        $this->dropDownList = $dropDownList;

        return $this->in(array_keys($this->dropDownList));
    }

    /**
     * Картинка
     * @param null|array $mimeTypes
     * @param array $rule
     * @return FormField
     */
    public function image($mimeTypes = null, array $rule = [])
    {
        $this->typeInit = true;
        $this->isFileField = true;

        $rule['mimeTypes'] = $mimeTypes;

        array_unshift($rule, __FUNCTION__);

        return $this->rule($rule);
    }

    /**
     * Картинка-свойство
     * @param $fieldName
     * @param null $mimeTypes
     * @param array $rule
     * @return FormField
     */
    public function linkImage($fieldName, $mimeTypes = null, array $rule = [])
    {
        $this->linkFieldName = $fieldName;

        return $this->image($mimeTypes, $rule);
    }

    /**
     * Удаляем картинку
     * @param $fieldName
     * @param null|string $notify
     * @return FormField
     */
    public function removeLinkFile($fieldName, $notify = null)
    {
        return $this->action(function (FormField $field, FormModel $form) use ($fieldName, $notify) {
            if (!$field->value) {
                return false;
            }
            if ($notify !== null) {
                $form->notifySuccess($notify);
            }
            $form->getModel()->unlinkFileByFieldName($fieldName);
            return true;
        });
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function action(callable $callback)
    {
        $this->boolean();
        $this->actionCallback = $callback;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLinkFieldName()
    {
        return $this->linkFieldName;
    }

    /**
     * @return array
     */
    public function getDropDownList()
    {
        return $this->dropDownList;
    }

    /**
     * Добавит правило
     * @param $rule
     * @return $this
     */
    public function rule($rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Вернет все правила
     * @return array
     */
    public function getRules()
    {
        if (!$this->typeInit) {
            $this->string();
        }

        return array_map(function ($rule) {
            array_unshift($rule, $this->name);
            return $rule;
        }, $this->rules);
    }

    /**
     * @return bool
     */
    public function isFileField()
    {
        return $this->isFileField;
    }

    /**
     * @return callable|null
     */
    public function getActionCallback()
    {
        return $this->actionCallback;
    }

    /**
     * @return bool
     */
    public function isActiveField()
    {
        return !$this->isFileField() && !$this->actionCallback;
    }
}
