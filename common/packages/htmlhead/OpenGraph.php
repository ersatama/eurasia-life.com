<?php

namespace common\packages\htmlhead;

use Yii;
use yii\helpers\Url;

/**
 * Class OpenGraph
 * @package common\packages\htmlhead
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class OpenGraph extends \yii\base\BaseObject implements ServiceInterface
{
    const TYPE_WEBSITE = 'website';

    const TYPE_ARTICLE = 'article';

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $type = self::TYPE_WEBSITE;

    /**
     * @var string
     */
    public $image; // 600x315

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $siteName = 'Евразия Лайф';

    /**
     * @var string
     */
    public $locale = 'ru_RU';

    /**
     * @var HtmlHead
     */
    public $htmlHead;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $map = [
            'ru' => 'ru_RU',
            'kz' => 'kk_KZ',
            'en' => 'en_US',
        ];

        $this->locale = $map[Yii::$app->language] ?? 'ru_RU';
    }

    /**
     * @return OpenGraph
     */
    public function changeTypeToArticle(): self
    {
        $this->type = static::TYPE_ARTICLE;

        return $this;
    }

    /**
     * @param string|null $title
     * @param string|null $content
     * @param string|null $image
     */
    public function article(?string $title, ?string $content, ?string $image): void
    {
        $title && $this->title = $title;

        $content && $this->description = $content;

        $image && $this->image = $image;

        $this->changeTypeToArticle();
    }

    /**
     *
     */
    public function register(): void
    {
        $this
            ->registerTitle()
            ->registerType()
            ->registerUrl()
            ->registerImage()
            ->registerDescription()
            ->registerSiteName()
            ->registerLocale();
    }

    /**
     * @return OpenGraph
     */
    protected function registerTitle(): self
    {
        $content = $this->title ?? $this->htmlHead->title ?? $this->htmlHead->view->title;

        return $this->metaProperty('og:title', $content);
    }

    /**
     * @return OpenGraph
     */
    protected function registerType(): self
    {
        $content = $this->type ?? 'website';

        return $this->metaProperty('og:type', $content);
    }

    /**
     * @return OpenGraph
     */
    protected function registerUrl(): self
    {
        $content = $this->url ?? $this->htmlHead->url ?? Url::canonical();

        return $this->metaProperty('og:url', $content);
    }

    /**
     * @return OpenGraph
     */
    protected function registerImage(): self
    {
        $content = $this->image ?? $this->htmlHead->image;

        return $this->metaProperty('og:image', $content);
    }

    /**
     * @return OpenGraph
     */
    protected function registerDescription(): self
    {
        $content = $this->description ?? $this->htmlHead->description;

        return $this->metaProperty('og:description', $content);
    }

    /**
     * @return OpenGraph
     */
    protected function registerSiteName(): self
    {
        $content = $this->siteName ?? Yii::$app->request->hostName;

        return $this->metaProperty('og:site_name', $content);
    }

    /**
     * @return OpenGraph
     */
    protected function registerLocale(): self
    {
        $content = $this->locale ?? str_replace('-', '_', Yii::$app->language);

        return $this->metaProperty('og:locale', $content);
    }

    /**
     * @param string $property
     * @param string|null $content
     * @param string|null $key
     * @return OpenGraph
     */
    protected function metaProperty(string $property, ?string $content, ?string $key = null): self
    {
        $this->htmlHead->metaProperty($property, $content, $key);

        return $this;
    }
}
