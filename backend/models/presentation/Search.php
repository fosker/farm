<?php

namespace backend\models\presentation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Presentation;
use common\models\presentation\City;
use common\models\presentation\Pharmacy;

class Search extends Presentation
{

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title'], 'string'],
            [['city_id', 'firm_id'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['city_id', 'firm_id']);
    }

    public function search($params)
    {
        $query = Presentation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            Presentation::tableName().'.id' => $this->id,
            'status' => $this->status,
        ]);

        $cities = City::find()->select('presentation_id')->andFilterWhere(['in', 'city_id', $this->getAttribute('city_id')]);
        $firms = Pharmacy::find()->select('presentation_id')->andFilterWhere(['in', 'firm_id', $this->getAttribute('firm_id')])
            ->joinWith('pharmacy');

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['in', Presentation::tableName().'.id', $cities])
            ->andFilterWhere(['in', Presentation::tableName().'.id', $firms]);

        $query->groupBy(Presentation::tableName().'.id');

        return $dataProvider;
    }
}
