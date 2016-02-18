<?php

namespace backend\models\firm;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\agency\Firm;

class Search extends Firm
{

    public function rules()
    {
        return [
            ['name', 'string'],
            ['id', 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Firm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
