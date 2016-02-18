<?php

namespace backend\models\seminar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Seminar;
use common\models\seminar\Pharmacy;
use common\models\seminar\City;

class Search extends Seminar
{

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title','email'], 'string'],
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
        $query = Seminar::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            Seminar::tableName().'.id' => $this->id,
            'status' => $this->status,
        ]);

        $cities = City::find()->select('seminar_id')->andFilterWhere(['in', 'city_id', $this->getAttribute('city_id')]);
        $firms = Pharmacy::find()->select('seminar_id')->andFilterWhere(['in', 'firm_id', $this->getAttribute('firm_id')])
            ->joinWith('pharmacy');

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['in', Seminar::tableName().'.id', $cities])
            ->andFilterWhere(['in', Seminar::tableName().'.id', $firms]);

        $query->groupBy(Seminar::tableName().'.id');

        return $dataProvider;
    }
}
