<?php

namespace backend\models\factory\stock;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\factory\Stock;
use common\models\factory\City;
use common\models\factory\Pharmacy;

class Search extends Stock
{

    public function rules()
    {
        return [
            [['id', 'factory_id', 'status'], 'integer'],
            ['title', 'string'],
            [['city_id', 'firm_id'], 'safe'],
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
        $query = Stock::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'factory_id' => $this->factory_id,
            'status' => $this->status,
        ]);

        $cities = City::find()->select('stock_id')->andFilterWhere(['in', 'city_id', $this->getAttribute('city_id')]);
        $firms = Pharmacy::find()->select('stock_id')->andFilterWhere(['in', 'firm_id', $this->getAttribute('firm_id')])
            ->joinWith('pharmacy');

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['in', Stock::tableName().'.id', $cities])
            ->andFilterWhere(['in', Stock::tableName().'.id', $firms]);

        $query->groupBy(Stock::tableName().'.id');
        
        return $dataProvider;
    }
}
