<?php

namespace common\traits;

/**
 * Class TitleOrNameTrait – Вернет значение поля Title, если оно есть, иначе вернет значение name
 * - Полезно для моделей с полями Наименование и Заголовок
 *
 * @property string $title
 * @property string $name
 * @property string $titleOrName
 *
 * @package common\traits
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait TitleOrNameTrait
{
    /**
     * Вернет title если он есть, иначе name
     * @return string
     */
    public function getTitleOrName()
    {
        return ($t = $this->title) ? $t : $this->name;
    }
}
