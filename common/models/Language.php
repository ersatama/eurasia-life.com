<?php

namespace common\models;

use yii\base\Model;

/**
 * Class Language – Язык на сайте
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Language extends Model
{
    const ID_RU = 1;
    const ID_KZ = 2;
    const ID_EN = 3;

    const SLUG_RU = 'ru';
    const SLUG_KZ = 'kz';
    const SLUG_EN = 'en';

    /**
     * Данные моделей
     * @return array
     */
    protected static function data(): array
    {
        return [
            static::dataRowBuilder(static::ID_RU, 'русский', static::SLUG_RU),
            static::dataRowBuilder(static::ID_KZ, 'казахский', static::SLUG_KZ),
            static::dataRowBuilder(static::ID_EN, 'английский', static::SLUG_EN),
        ];
    }

    /**
     * @param string $slug
     * @return int|null
     */
    public static function getIdBySlug(string $slug): ?int
    {
        foreach (static::getAllWithCache() as $language) {
            if ($language->slug == $slug) {
                return $language->id;
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @return string|null
     */
    public static function getSlugById(int $id): ?string
    {
        foreach (static::getAllWithCache() as $language) {
            if ($language->id == $id) {
                return $language->slug;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getAllSlugs(): array
    {
        return array_map(function (self $lang) {
            return $lang->slug;
        }, static::getAllWithCache());
    }

    /**
     * Вернет все языки
     * @return static[]
     */
    public static function getAll(): array
    {
        return array_map(function ($data) {
            return new static($data);
        }, static::data());
    }

    /**
     * @var self[]
     */
    protected static $all;

    /**
     * @return self[]
     */
    public static function getAllWithCache(): array
    {
        return (static::$all === null) ? (static::$all = static::getAll()) : static::$all;
    }

    /**
     * Помогайка собирает массив данных
     * @param int $id
     * @param string $name
     * @param string $slug
     * @return array
     */
    protected static function dataRowBuilder(int $id, string $name, string $slug): array
    {
        return [
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
        ];
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;
}
