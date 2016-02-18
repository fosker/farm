<?php

namespace backend\models\report\answer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\report\Sent;

/**
 * Search represents the model behind the search form about `common\models\report\Sent`.
 */
class Search extends Sent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item.report_id'], 'integer'],
            [['date_send','item.report.title','user.name'], 'string'],
        ];
    }

    public function attributes() {
        return array_merge(parent::attributes(), ['item.report.title','user.name', 'item.report_id']);
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
        $query = Sent::find()->joinWith(['item.report','user'])->groupBy('report_product.report_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'date_send'=>SORT_ASC,
                    'id' => SORT_DESC
                ],
            ],
        ]);

        $dataProvider->sort->attributes['item.report.title'] = [
            'asc' => ['report.title' => SORT_ASC],
            'desc' => ['report.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['user.name'] = [
            'asc' => ['user.name' => SORT_ASC],
            'desc' => ['user.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'report_product.report_id'=>$this->getAttribute('item.report_id'),
            'date_send' => $this->date_send,
        ]);

        $query->andFilterWhere(['like', 'user.name', $this->getAttribute('user.name')])
            ->andFilterWhere(['like', 'report.title', $this->getAttribute('item.report.title')]);

        return $dataProvider;
    }
}
