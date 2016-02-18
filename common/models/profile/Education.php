<?php

namespace common\models\profile;

use Yii;

/**
 * This is the model class for table "user_education".
 *
 * @property integer $id
 * @property string $name
 */
class Education extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'user_education';
    }

    public function rules()
    {
        return [
            ['name', 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название образования',
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // TODO: удалить все связи
    }
}
