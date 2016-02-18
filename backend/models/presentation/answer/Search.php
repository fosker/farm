<?php

namespace backend\models\presentation\answer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Presentation;
use common\models\User;
use common\models\presentation\Answer;
use common\models\presentation\View;

class Search extends Answer
{

    public function rules()
    {
        return [
            [['view.presentation.id'], 'integer'],
            [['view.presentation.title', 'view.user.login'], 'string'],
        ];
    }

    public function attributes() {
        return array_merge(parent::attributes(), ['view.presentation.title','view.user.login', 'view.presentation.id', 'view.added']);
    }

    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Answer::find()->joinWith(['view','view.presentation', 'view.user']);
        $query->groupBy('presentation_views.presentation_id, presentation_views.user_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'id' => SORT_DESC
                ],
            ],
        ]);

        $dataProvider->sort->attributes['view.presentation.title'] = [
            'asc' => [Presentation::tableName().'.title' => SORT_ASC],
            'desc' => [Presentation::tableName().'.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['view.presentation.id'] = [
            'asc' => [Presentation::tableName().'.id' => SORT_ASC],
            'desc' => [Presentation::tableName().'.id' => SORT_DESC],
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
            Presentation::tableName().'.id' => $this->getAttribute('view.presentation.id'),
        ]);

        $query->andFilterWhere(['like', User::tableName().'.login', $this->getAttribute('view.user.login')])
            ->andFilterWhere(['like', Presentation::tableName().'.title', $this->getAttribute('view.presentation.title')]);

        return $dataProvider;
    }
}
