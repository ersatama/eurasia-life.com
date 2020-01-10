<?php

namespace common\models;

use yii\base\Model;

/**
 * Class LandingPage – Посадочная страница
 *
 * @property string $name
 * @see LandingPage::getName()
 *
 * @property string $slug
 * @see LandingPage::getSlug()
 *
 * @property string $title
 * @see LandingPage::getTitle()
 *
 * @property string $visible
 * @see LandingPage::getVisible()
 *
 * @property File $mainImageFile
 * @see LandingPage::getMainImageFile()
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LandingPage extends Model
{
    /**
     * Данные моделей
     * @return array
     */
    protected static function data(): array
    {
        $return = [];

        $langRuId = Language::ID_RU;
        $langKzId = Language::ID_KZ;
        $langEnId = Language::ID_EN;

        // ru
        if ($langRuId) {
            $return = array_merge($return, [
                // Страхование работников от&nbsp;несчастных случаев
                static::dataRowBuilder(
                    1,
                    $langRuId,
                    [5, 9]
                ),
                // Аннуитетное страхование
                static::dataRowBuilder(
                    2,
                    $langRuId,
                    [6, 10]
                ),
                // Страхование жизни
                static::dataRowBuilder(
                    3,
                    $langRuId,
                    [7, 11]
                ),
                // Пенсионный аннуитет
                static::dataRowBuilder(
                    4,
                    $langRuId,
                    [8, 12]
                ),
            ]);
        }

        // kz
        if ($langKzId) {
            $return = array_merge($return, [
                // [kz] Страхование работников от&nbsp;несчастных случаев
                static::dataRowBuilder(
                    5,
                    $langKzId,
                    [1, 9]
                ),
                // [kz] Аннуитетное страхование
                static::dataRowBuilder(
                    6,
                    $langKzId,
                    [2, 10]
                ),
                // [kz] Страхование жизни
                static::dataRowBuilder(
                    7,
                    $langKzId,
                    [3, 11]
                ),
                // [kz] Пенсионный аннуитет
                static::dataRowBuilder(
                    8,
                    $langKzId,
                    [4, 12]
                ),
            ]);
        }

        // en
        if ($langEnId) {
            $return = array_merge($return, [
                // [en] Страхование работников от&nbsp;несчастных случаев
                static::dataRowBuilder(
                    9,
                    $langEnId,
                    [1, 5]
                ),
                // [en] Аннуитетное страхование
                static::dataRowBuilder(
                    10,
                    $langEnId,
                    [2, 6]
                ),
                // [en] Страхование жизни
                static::dataRowBuilder(
                    11,
                    $langEnId,
                    [3, 7]
                ),
                // [en] Пенсионный аннуитет
                static::dataRowBuilder(
                    12,
                    $langEnId,
                    [4, 8]
                ),
            ]);
        }

        return $return;
    }

    /**
     * Загружает шорткоды для посадочных страниц
     * @param array $landingPages
     * @param array|null $shortCodeList
     * @param bool $withFiles
     */
    public static function loadShortCodes(array $landingPages, array $shortCodeList = null, bool $withFiles = true)
    {
        $forList = [];
        foreach ($landingPages as $landingPage) {
            $forList[$landingPage->getShortCodeName()] = $landingPage;
        }

        if ($forList) {
            $query = ShortCode::find();
            $query->active();
            $query->andWhere(['for' => array_keys($forList)]);
            $shortCodeList && $query->andWhere(['short_code' => $shortCodeList]);
            $withFiles && $query->with('files');

            foreach ($query->all() as $shortCode) {
                if (isset($forList[$shortCode->for])) {
                    $forList[$shortCode->for]->addShortCode($shortCode);
                }
            }
        }
    }

    /**
     * Отфильтрует по slug языка
     * @param self[] $models
     * @param string $slug
     * @return self[]
     */
    public static function filterByLangSlug(array $models, string $slug): array
    {
        $langId = Language::getIdBySlug($slug);
        return array_filter($models, function (self $model) use ($langId) {
            return $model->lang_id == $langId;
        });
    }

    /**
     * Отфильтрует по id языка
     * @param self[] $models
     * @param int $langId
     * @return self[]
     */
    public static function filterByLangId(array $models, int $langId): array
    {
        return array_filter($models, function (self $model) use ($langId) {
            return $model->lang_id == $langId;
        });
    }

    /**
     * Вернет все посадочные страницы
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
        if (static::$all === null) {
            static::$all = static::getAll();
            self::loadShortCodes(static::$all, ['name', 'slug', 'visible'], false);
        }

        return static::$all;
    }

    /**
     * Помогайка собирает массив данных
     * @param int $id
     * @param int $langId
     * @param array $translationIds
     * @return array
     */
    protected static function dataRowBuilder(int $id, int $langId, array $translationIds): array
    {
        return [
            'id' => $id,
            'lang_id' => $langId,
            'translation_ids' => $translationIds
        ];
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $lang_id;

    /**
     * @var array
     */
    public $translation_ids = [];

    /**
     * @var ShortCode[]
     */
    protected $shortCodes = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return ($t = $this->getShortCodeContent('name')) ? $t : $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return ($t = $this->getShortCodeContent('slug')) ? $t : sprintf('landing-%d', $this->id);
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return !!$this->getShortCodeContent('visible');
    }

    /**
     * @return string
     */
    public function getShortCodeName(): string
    {
        return 'landing-page-' . $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getShortCodeContent('h1', '');
    }

    /**
     * Вернет главную картинку
     * @return File
     */
    public function getMainImageFile()
    {
        if (isset($this->shortCodes['image']) && ($files = $this->shortCodes['image']->files) && ($file = current($files))) {
            return $file;
        }
        return null;
    }

    /**
     * @param ShortCode $shortCode
     */
    public function addShortCode(ShortCode $shortCode)
    {
        $this->shortCodes[$shortCode->short_code] = $shortCode;
    }

    /**
     * @param string $shortCode
     * @param null $default
     * @return mixed
     */
    public function getShortCodeContent(string $shortCode, $default = null)
    {
        return isset($this->shortCodes[$shortCode]) ? $this->shortCodes[$shortCode]->content : $default;
    }

    /**
     * @return array
     */
    public function getTranslationMap(): array
    {
        $return = [];

        $landingPages = [$this->lang_id => $this];
        foreach (self::getAllWithCache() as $landingPage) {
            if (!in_array($landingPage->id, $this->translation_ids) || !$landingPage->isVisible()) {
                continue;
            }
            $landingPages[$landingPage->lang_id] = $landingPage;
        }

        foreach (Language::getAllWithCache() as $language) {
            $return[$language->id] = $landingPages[$language->id] ?? null;
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function isStrahovanieRabotnikovOtNeschastnyhSluchaev(): bool
    {
        return in_array($this->id, [1, 5, 9]);
    }

    /**
     * @return bool
     */
    public function isAnnuitetnoeStrahovanieVRamkahOsns(): bool
    {
        return in_array($this->id, [2, 6, 10]);
    }

    /**
     * @return bool
     */
    public function isStrahovanieZhizniZayomshhikovPoKreditam(): bool
    {
        return in_array($this->id, [3, 7, 11]);
    }

    /**
     * @return bool
     */
    public function isPensionnyjAnnuitet(): bool
    {
        return in_array($this->id, [4, 8, 12]);
    }
}
