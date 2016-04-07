<?php

namespace backend\models\news;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;
use common\models\news\View;
use common\models\news\City;
use common\models\news\Pharmacy;


class Search extends News
{

    public function rules()
    {
        return [
            [['id', 'views'], 'integer'],
            [['title', 'text', 'date'], 'string'],
            [['city_id', 'firm_id'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributes() {
        return array_merge(parent::attributes(),['city_id', 'firm_id']);
    }

    public function search($params)
    {
        $query = News::find();

        $viewsQuery = View::find()
            ->select('count(DISTINCT user_id) as count, news_id')
            ->groupBy('news_id');
        $query->leftJoin(['viewsCount' => $viewsQuery], 'viewsCount.news_id = id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'date' => SORT_DESC
                ],
            ],
        ]);

        $dataProvider->sort->attributes['views'] = [
            'asc' => ['viewsCount.count' => SORT_ASC],
            'desc' => ['viewsCount.count' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if($this->views)
            $query->andFilterWhere(['(viewsCount.count + views_added)' => ($this->views + $this->views_added)]);

        if($this->getAttribute('city_id'))
            $cities = City::find()->select('news_id')->where(['in', 'city_id', $this->getAttribute('city_id')]);
        if($this->getAttribute('firm_id'))
            $firms = Pharmacy::find()->select('news_id')->where(['in', 'firm_id', $this->getAttribute('firm_id')])
                ->joinWith('pharmacy');

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['in', News::tableName().'.id', $cities])
            ->andFilterWhere(['in', News::tableName().'.id', $firms]);

        return $dataProvider;
    }
}