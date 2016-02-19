<?php

namespace backend\models\factory\answer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\factory\Reply;

/**
 * Search represents the model behind the search form about `common\models\factory\Reply`.
 */
class Search extends Reply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stock_id', 'user_id'], 'integer'],
            [['photo'], 'safe'],
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
        $query = Reply::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'stock_id' => $this->stock_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
