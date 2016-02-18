<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_vendors".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 */
class Vendor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_vendors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['email', 'email']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название поставщика',
            'email' => 'Email'
        ];
    }

}
