<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\models\Language;
use common\models\PostArticle;
use common\models\PostArticleQuery;

/**
 * Class PostArticleSearchForm – Поиск по публикациям
 *
 * @package frontend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostArticleSearchForm extends Model
{
    /**
     * @var int – кол-во позиций
     */
    public $pageSize = 20;

    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;

    /**
     * @var string
     */
    public $language;

    /**
     * Поиск
     * @param array $params
     */
    public function search(array $params = [])
    {
        $this->searchDb($params);
    }

    /**
     * Поиск по БД
     * @param array $params
     */
    protected function searchDb(array $params = [])
    {
        $query = PostArticle::find();
        $this->postArticleQuery($query);

        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere([
                'or',
                ['like', 'name', $this->q],
//                ['like', 'announce', $this->q],
                ['like', 'content', $this->q]
            ]);
        }

        $this->dataProvider($query);
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * @param PostArticleQuery $query
     */
    protected function postArticleQuery(PostArticleQuery $query)
    {
        $query->active();
        $query->visible();
        $query->published();
        $query->andWhere(['lang_id' => Language::getIdBySlug($this->language)]);
        $query->sortByPosition();
        $query->withMainImage();
    }

    /**
     * @param Query $query
     */
    protected function dataProvider(Query $query)
    {
        $this->dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'defaultPageSize' => $this->pageSize,
            ],
        ]);
    }
}
