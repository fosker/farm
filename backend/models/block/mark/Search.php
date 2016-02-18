<?php

namespace backend\models\block\mark;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\block\Mark;

/**
 * Search represents the model behind the search form about `common\models\block\Mark`.
 */
class Search extends Mark
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id', 'mark'], 'integer'],
            [['user.name'], 'string'],
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

    public function attributes() {
        return array_merge(parent::attributes(), ['user.name']);
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
        $query = Mark::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'date_add'=>SORT_ASC,
                    'id' => SORT_DESC
                ],
            ],
        ]);

        $query->joinWith(['user' => function($query) { $query->from(['user' => 'users']); }]);
        $dataProvider->sort->attributes['user.name'] = [
            'asc' => ['user.name' => SORT_ASC],
            'desc' => ['user.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            static::tableName().'.id' => $this->id,
            'block_id' => $this->block_id,
            'mark' => $this->mark,
        ]);

        $query->andFilterWhere(['like', 'user.name', $this->getAttribute('user.name')]);

        return $dataProvider;
    }
}
