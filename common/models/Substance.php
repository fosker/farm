<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "substances".
 *
 * @property integer $id
 * @property string $cyrillic
 * @property string $name
 * @property string $description
 */
class Substance extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'substances';
    }

    public function rules()
    {
        return [
            [['cyrillic', 'name', 'description'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cyrillic' => 'Название вещества',
            'name' => 'На латинском',
            'description' => 'Описание',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'cyrillic',
            'name',
        ];
    }

    public function extraFields()
    {
        return ['description'];
    }

    public static function findByPart($part)
    {
        return static::Find()->filterWhere(['like','cyrillic',$part])->orFilterWhere(['like','name',$part]);
    }
}
