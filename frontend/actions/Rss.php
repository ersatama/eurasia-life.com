<?php

namespace frontend\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use common\models\PostArticle;

/**
 * Class Rss — Простая rss-лента
 * @package frontend\actions
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Rss extends Action
{
    /**
     * @return \yii\console\Response|Response
     */
    public function run()
    {
        $query = PostArticle::find();
        $query->active();
        $query->visible();
        $query->sortByPublishAt();
        $query->limit(20);

        $postArticles = $query->all();

        $feed = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"><channel></channel></rss>');
        $feed->channel->addChild('title', 'Евразия лайф');
        $feed->channel->addChild('link', 'https://eurasia-life.com');
        $feed->channel->addChild('generator', 'eurasia-life.com');

        foreach ($postArticles as $postArticle) {
            $pubDate = date('r', $postArticle->publish_at);
            $link = $postArticle->fullUrl;
            $title = html_entity_decode(strip_tags($postArticle->name));
//            $description = html_entity_decode(strip_tags($postArticle->announce));

            if (substr($link, 0, 2) == '//') {
                $link = 'http:' . $link;
            }

            $row = $feed->channel->addChild('item');
            $row->addChild('pubDate', $pubDate);
            $row->addChild('link', $link);
            $row->addChild('title', $title);
//            $row->addChild('description', $description);
        }

        $data = $feed->asXML();

        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;
        $response->data = $data;
        $response->getHeaders()->set('content-type', 'text/xml');

        return $response;
    }
}
