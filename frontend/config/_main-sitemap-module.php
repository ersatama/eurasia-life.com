<?php

use himiklab\sitemap\behaviors\SitemapBehavior;

use common\models\Page;
use common\models\PageQuery;
use common\models\PostArticle;
use common\models\PostArticleQuery;

// @todo: check

/**
 * sitemap.xml
 */
return [
    'class' => 'himiklab\sitemap\Sitemap',
    'models' => [
        // Content pages
        [
            'class' => Page::class,
            'behaviors' => [
                'sitemap' => [
                    'class' => SitemapBehavior::class,
                    'scope' => function (PageQuery $pageQuery) {
                        $pageQuery->withoutRoot();
                        $pageQuery->active();
                        $pageQuery->visible();
                        $pageQuery->sort();
                    },
                    'dataClosure' => function (Page $page) {
                        return [
                            'loc' => $page->url,
                            'lastmod' => ($t = $page->updated_at) ? $t : $page->created_at,
                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                            'priority' => 0.7
                        ];
                    }
                ],
            ],
        ],
        // news
        [
            'class' => PostArticle::class,
            'behaviors' => [
                'sitemap' => [
                    'class' => SitemapBehavior::class,
                    'scope' => function (PostArticleQuery $postArticleQuery) {
                        $postArticleQuery->active();
                        $postArticleQuery->visible();
                        $postArticleQuery->published();
                        $postArticleQuery->sortByPublishAt();
                    },
                    'dataClosure' => function (PostArticle $postArticle) {
                        return [
                            'loc' => $postArticle->url,
                            'lastmod' => ($t = $postArticle->updated_at) ? $t : $postArticle->created_at,
                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                            'priority' => 0.8
                        ];
                    }
                ],
            ],
        ],
    ],
    'urls' => [

    ],
    'enableGzip' => true, // default is false
    'cacheExpire' => 60 * 5, // Default is 24 hours
];
