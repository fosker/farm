<?php

namespace backend\models\factory\answer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\factory\Reply;
use common\models\factory\Stock;
use common\models\User;

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
            [['id', 'stock_id'], 'integer'],
            [['stock.title', 'user.login'], 'string'],
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
        return array_merge(parent::attributes(), ['stock.title', 'user.login']);
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
        $query = Reply::find()->joinWith(['user','stock']);;


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'stock_id'=>SORT_DESC,
                ],
            ],
        ]);

        $dataProvider->sort->attributes['user.login'] = [
            'asc' => [User::tableName().'.login' => SORT_ASC],
            'desc' => [User::tableName().'.login' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['stock.title'] = [
            'asc' => [Stock::tableName().'.title' => SORT_ASC],
            'desc' => [Stock::tableName().'.title' => SORT_DESC],
        ];

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
        ]);

        $query->andFilterWhere(['like', User::tableName().'.login', $this->getAttribute('user.login')])
            ->andFilterWhere(['like', Stock::tableName().'.title', $this->getAttribute('stock.title')]);

        return $dataProvider;
    }
}
