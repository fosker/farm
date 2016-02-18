<?php

namespace backend\models\profile\present;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\shop\Present;
use common\models\User;
use common\models\Item;

class Search extends Present
{

    public function rules()
    {
        return [
            [['count'], 'integer'],
            [['promo','item.title', 'user.login'], 'string'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['user.name','item.title', 'user.login']);
    }

    public function search($params)
    {
        $query = Present::find()->joinWith(['user','item']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder'=>[
                    'date_buy'=>SORT_DESC,
                ],
            ],
        ]);

        $dataProvider->sort->attributes['user.login'] = [
            'asc' => [User::tableName().'.login' => SORT_ASC],
            'desc' => [User::tableName().'.login' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['item.title'] = [
            'asc' => [Item::tableName().'.title' => SORT_ASC],
            'desc' => [Item::tableName().'.title' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'count' => $this->count,
        ]);

        $query->andFilterWhere(['like', 'promo', $this->promo])
            ->andFilterWhere(['like', User::tableName().'.login', $this->getAttribute('user.login')])
            ->andFilterWhere(['like', Item::tableName().'.title', $this->getAttribute('item.title')]);

        return $dataProvider;
    }
}
