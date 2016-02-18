<?php

namespace backend\models\partner;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Partner;

/**
 * PartnerSearch represents the model behind the search form about `common\models\Partner`.
 */
class Search extends Partner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'status'], 'integer'],
            [['logo', 'name', 'description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Partner::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'priority'=>SORT_DESC,
                    'id' => SORT_DESC
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            static::tableName().'.id' => $this->id,
            'priority' => $this->priority,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
