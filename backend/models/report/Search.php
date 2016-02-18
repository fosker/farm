<?php

namespace backend\models\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Report;

/**
 * Search represents the model behind the search form about `common\models\Report`.
 */
class Search extends Report
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id','period', 'type_id', 'status'], 'integer'],
            [['title','factory.title'], 'safe'],
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
        return array_merge(parent::attributes(), ['factory.title','factory_id']);
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
        $query = Report::find()->joinWith('factory');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['factory.title'] = [
            'asc' => ['factory.title' => SORT_ASC],
            'desc' => ['factory.title' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'factory_id' => $this->factory_id,
            'period' => $this->period,
            'type_id' => $this->type_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
        ->andFilterWhere(['like', 'report_factory.title', $this->getAttribute('factory.title')]);
        return $dataProvider;
    }
}
