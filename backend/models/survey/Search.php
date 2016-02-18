<?php

namespace backend\models\survey;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Survey;
use common\models\survey\Pharmacy;
use common\models\survey\City;
use yii\db\Query;

class Search extends Survey
{

    public function rules()
    {
        return [
            [['id', 'status', 'points'], 'integer'],
            [['title'], 'string'],
            [['city_id', 'firm_id'], 'safe']
        ];
    }

    public function attributes() {
        return array_merge(parent::attributes(),['city_id', 'firm_id']);
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Survey::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            Survey::tableName().'.id' => $this->id,
            'status' => $this->status,
            'points' => $this->points,
        ]);

        $cities = City::find()->select('survey_id')->andFilterWhere(['in', 'city_id', $this->getAttribute('city_id')]);
        $firms = Pharmacy::find()->select('survey_id')->andFilterWhere(['in', 'firm_id', $this->getAttribute('firm_id')])
            ->joinWith('pharmacy');

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['in', Survey::tableName().'.id', $cities])
            ->andFilterWhere(['in', Survey::tableName().'.id', $firms]);

        $query->groupBy(Survey::tableName().'.id');

        return $dataProvider;
    }
}
