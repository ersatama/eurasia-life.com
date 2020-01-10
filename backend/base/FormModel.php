<?php

namespace backend\base;

use yii\base\Model;
use yii\db\ActiveRecord;
use common\behaviors\NotifyBehavior;

/**
 * Class FormModel – Базовый класс форм
 *
 * @mixin NotifyBehavior
 *
 * @package backend\base
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
abstract class FormModel extends Model
{
    const SCENARIO_CREATE = 'create';

    const IMAGE_TYPES = [
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/gif',
    ];

    /**
     * @var ActiveRecord
     */
    protected $model;

    /**
     * @var FormField[]
     */
    protected $fields = [];

    /**
     * @inheritdoc
     */
    public function __construct($model, array $config = [])
    {
        $this->model = $model;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $attributes = $this->model->getAttributes($this->getActiveAttributes());

        $this->setAttributes($attributes, false);
    }

    /**
     * @return array
     */
    public function getActiveAttributes()
    {
        $return = [];
        foreach ($this->fields as $field) {
            if ($field->isActiveField()) {
                $return[] = $field->name;
            }
        }
        return $return;
    }

    /**
     * @return array
     */
    public function getFileFields()
    {
        $return = [];
        foreach ($this->fields as $field) {
            if ($field->isFileField()) {
                $return[] = $field->name;
            }
        }
        return $return;
    }

    /**
     * Добавит новое поле
     * @param string $name
     * @param null|string $label
     * @return FormField
     */
    protected function field($name, $label = null)
    {
        return $this->fields[$name] = new FormField($this, [
            'name' => $name,
            'label' => $label,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        $return = parent::attributes();
        foreach ($this->fields as $field) {
            $return[] = $field->name;
        }
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $return = parent::attributeLabels();
        foreach ($this->fields as $field) {
            $return[$field->name] = $field->label;
        }
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $return = parent::rules();
        foreach ($this->fields as $field) {
            $return = array_merge($return, $field->getRules());
        }
        return $return;
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
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_CREATE] = $scenarios[static::SCENARIO_DEFAULT];
        return $scenarios;
    }

    /**
     * Перед сохранением записи
     */
    protected function beforeSave()
    {
        $attributes = $this->getAttributes($this->getActiveAttributes());

        $this->model->setAttributes($attributes, false);

        $this->beforeSaveLoadUploadFiles();

        $this->beforeSaveChangeCreatedUpdatedAttributes();
    }

    /**
     * Перед сохранением изменим атрибуты создания и обновления
     */
    protected function beforeSaveChangeCreatedUpdatedAttributes()
    {
        $model = $this->model;
        if ($this->scenario === static::SCENARIO_CREATE) {
            $model->setCreatedAttributes();
            $model->changeStatusToActive();
        } else {
            $model->setUpdatedAttributes();
        }
    }

    /**
     * Перед сохранением загрузим файлы
     */
    protected function beforeSaveLoadUploadFiles()
    {
        $model = $this->getModel();

        foreach ($this->getFileFields() as $fileFieldName) {
            $field = $this->fields[$fileFieldName];
            if (!$field->value) {
                continue;
            }

            $linkFieldName = $field->getLinkFieldName();

            if ($linkFieldName) {
                $file = $model->linkUploadedFile($field->value, $linkFieldName);
            } else {
                $file = $model->addUploadedFile($field->value);
            }

            if ($file) {
                foreach ($field->getRules() as $rule) {
                    if (is_array($rule) && isset($rule[1]) && $rule[1] === 'image') {
                        $file->imageOptimize();
                        break;
                    }
                }
            }
        }
    }

    /**
     * Сохраняем
     * @param bool $runValidation
     * @param null|array $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->buttonActions() === true) {
            return false;
        }

        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }

        if ($this->beforeSave() === false) {
            return false;
        }

        $return = $this->model->saveWithException();

        $this->afterSave();

        return $return;
    }

    /**
     * После сохранения
     */
    protected function afterSave()
    {
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return isset($this->fields[$name]) ? $this->fields[$name]->value : null;
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (isset($this->fields[$name])) {
            $this->fields[$name]->value = $value;
            return;
        }

        parent::__set($name, $value);
    }

    /**
     * @return ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->model->getPrimaryKey();
    }

    /**
     * Вернет список для селекта
     * @param string $fieldName
     * @param null|string $defaultValue
     * @return array
     */
    public function getDropDownList($fieldName, $defaultValue = null)
    {
        $return = $this->fields[$fieldName]->getDropDownList();

        if ($defaultValue !== null) {
            $return = ['' => $defaultValue] + $return;
        }

        return $return;
    }

    /**
     * Действия кнопок
     * @return bool
     */
    protected function buttonActions()
    {
        foreach ($this->fields as $field) {
            $callback = $field->getActionCallback();
            if (is_callable($callback) && $callback($field, $this) === true) {
                return true;
            }
        }
        return false;
    }
}
