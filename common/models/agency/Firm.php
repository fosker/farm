<?php

namespace common\models\agency;

use Yii;

/**
 * This is the model class for table "firms".
 *
 * @property integer $id
 * @property string $name
 */
class Firm extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'firms';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название фирмы',
            'id' => 'ID'
        ];
    }

    public function getPharmacies()
    {
        return $this->hasMany(Pharmacy::className(),['id'=>'firm_id']);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Pharmacy::deleteAll(['firm_id'=>$this->id]);
    }
}
