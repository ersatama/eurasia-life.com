<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\models\Language;
use common\models\PostArticle;

/**
 * Class PostArticleSearchForm – Поиск по публикациям
 *
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostArticleSearchForm extends Model
{
    /**
     * @var int – кол-во позиций
     */
    public $pageSize = 100;

    /**
     * @var string - что ищем
     */
    public $q;

    /**
     * @var boolean - ищем по всем полям
     */
    public $full = false;

    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['q', 'string'],
            ['full', 'boolean'],
        ];
    }

    /**
     * Поиск
     * @param array $params
     */
    public function search(array $params = [])
    {
        $query = PostArticle::find();
        $query->active();
        $query->andWhere(['lang_id' => Language::ID_RU]);
        $query->sort();

        if ($this->load($params) && $this->validate()) {
            $filter = ['or', ['like', 'name', $this->q]];
            if ($this->full) {
//                array_push($filter, ['like', 'announce', $this->q]);
                array_push($filter, ['like', 'content', $this->q]);
            }
            $query->andFilterWhere($filter);
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
     * @param Query $query
     */
    protected function dataProvider(Query $query): void
    {
        $this->dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'defaultPageSize' => $this->pageSize,
                'pageSizeLimit' => [1, 100],
            ],
        ]);
    }
}
