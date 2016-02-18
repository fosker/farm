<?php

namespace backend\models\presentation\comment;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\presentation\Comment;
use common\models\User;

class Search extends Comment
{

    public function rules()
    {
        return [
            [['id', 'presentation_id'], 'integer'],
            [['comment','user.name'], 'string'],
        ];
    }

    public function attributes() {
        return array_merge(parent::attributes(), ['user.name']);
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Comment::find()->joinWith('user');;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'date_add'=>SORT_ASC,
                    'id' => SORT_DESC
                ],
            ],
        ]);
        $dataProvider->sort->attributes['user.name'] = [
            'asc' => [User::tableName().'.name' => SORT_ASC],
            'desc' => [User::tableName().'.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'presentation_id' => $this->presentation_id,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', User::tableName().'.name', $this->getAttribute('user.name')]);

        return $dataProvider;
    }
}
