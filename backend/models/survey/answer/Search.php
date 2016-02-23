<?php

namespace backend\models\survey\answer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\survey\Answer;
use common\models\Survey;
use common\models\User;
use common\models\survey\View;

class Search extends Answer
{
    public function rules()
    {
        return [
            [['view.survey.id', 'view.user.id'], 'integer'],
            [['view.survey.title', 'view.user.login', 'view.added'], 'string']
        ];
    }

    public function attributes() {
        return array_merge(parent::attributes(), ['view.survey.title', 'view.survey.id', 'view.user.login', 'view.added', 'view.user.id']);
    }
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Answer::find()->joinWith(['view', 'view.survey', 'view.user']);
        $query->groupBy('survey_views.survey_id, survey_views.user_id');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'view.added' => SORT_DESC
                ],
            ],
        ]);

        $dataProvider->sort->attributes['view.survey.title'] = [
            'asc' => [Survey::tableName().'.title' => SORT_ASC],
            'desc' => [Survey::tableName().'.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['view.survey.id'] = [
            'asc' => [Survey::tableName().'.id' => SORT_ASC],
            'desc' => [Survey::tableName().'.id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['view.user.login'] = [
            'asc' => [User::tableName().'.login' => SORT_ASC],
            'desc' => [User::tableName().'.login' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['view.added'] = [
            'asc' => [View::tableName().'.added' => SORT_ASC],
            'desc' => [View::tableName().'.added' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            Survey::tableName().'.id'=>$this->getAttribute('view.survey.id'),
        ]);

        $query->andFilterWhere(['like', Survey::tableName().'.title', $this->getAttribute('view.survey.title')])
            ->andFilterWhere(['like', View::tableName().'.added', $this->getAttribute('view.added')])
            ->andFilterWhere(['like', User::tableName().'.id', $this->getAttribute('view.user.id')]);

        return $dataProvider;
    }
}
