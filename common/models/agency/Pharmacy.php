<?php

namespace common\models\agency;

use Yii;
use common\models\location\City;

/**
 * This is the model class for table "firm_pharmacies".
 *
 * @property integer $id
 * @property string $address
 * @property string $name
 * @property integer $firm_id
 * @property integer $city_id
 */
class Pharmacy extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'firm_pharmacies';
    }

    public function rules()
    {
        return [
            [['firm_id', 'city_id', 'name'], 'required'],
            [['address'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название аптеки',
            'address' => 'Адрес',
            'firm_id' => 'Фирма',
            'city_id' => 'Город',
        ];
    }

    public function getFirm()
    {
        return $this->hasOne(Firm::className(), ['id' => 'firm_id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // TODO: поудалять связи
    }
}
