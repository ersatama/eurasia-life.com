<?php

namespace common\packages\htmlhead;

use Yii;
use yii\helpers\Url;
use yii\web\View;

/**
 * Class HtmlHead
 * @package common\packages\htmlhead
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class HtmlHead extends \yii\base\Component
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $keywords;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $image;

    /**
     * @var OpenGraph
     */
    public $openGraph;

    /**
     * @var View
     */
    public $view;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
//        if (!isset($config['image']) && !array_key_exists('image', $config)) {
        if (!isset($config['image']) || !$config['image']) {
            $config['image'] = Url::to('/share-big-v1.png', true);
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!($this->view instanceof View)) {
            $this->view = Yii::$app->view;
        }

        if (!($this->openGraph instanceof ServiceInterface)) {
            $this->openGraph = new OpenGraph([
                'htmlHead' => $this,
            ]);
        }

        $this->view->on(View::EVENT_END_PAGE, [$this, 'eventViewEndPage']);
    }

    /**
     *
     */
    public function eventViewEndPage()
    {
        // Common
        $this->register();

        // OpenGraph
        $this->openGraph->register();
    }

    protected function register()
    {
        $this->metaName('description', $this->description);

        $this->metaName('keywords', $this->keywords);

        // todo: удалить при https и правильных переадресаций!
        $href = $this->url ?? Url::canonical();
        $host = parse_url($href, PHP_URL_HOST);
        if ($host !== 'eurasia-life.com') {
            $href = str_replace($host, 'eurasia-life.com', $href);
        }

        $this->link('canonical', $href);

        if (($t = $this->image)) {
            $this->link('image_src', $t);
        }

        // @todo: twitter?
        $this->metaProperty('twitter:card', 'summary_large_image');
    }

    /**
     * @param string $type
     * @param string $name
     * @param string|null $content
     * @param string|null $key
     * @return HtmlHead
     */
    public function meta(string $type, string $name, ?string $content, ?string $key = null): self
    {
        if (strlen($content)) {
            $this->view->registerMetaTag([$type => $name, 'content' => $content], $key);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string|null $content
     * @param string|null $key
     * @return HtmlHead
     */
    public function metaName(string $name, ?string $content, ?string $key = null): self
    {
        return $this->meta('name', $name, $content, $key);
    }

    /**
     * @param string $property
     * @param string|null $content
     * @param string|null $key
     * @return HtmlHead
     */
    public function metaProperty(string $property, ?string $content, ?string $key = null): self
    {
        return $this->meta('property', $property, $content, $key);
    }

    /**
     * @param string $rel
     * @param string|null $href
     * @param array $options
     * @param string|null $key
     * @return HtmlHead
     */
    public function link(string $rel, ?string $href, array $options = [], ?string $key = null): self
    {
        $options['rel'] = $rel;

        if ($href !== null) {
            $options['href'] = $href;
        }

        $this->view->registerLinkTag($options, $key);

        return $this;
    }
}
