<?php

namespace common\models\profile;

use Yii;
use common\models\User;
use common\models\banner\Education as Banner_education;

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

    public function getUsers() {
        return $this->hasMany(User::className(), ['education_id' => 'id']);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Banner_education::deleteAll(['education_id' => $this->id]);
        foreach($this->users as $user)
            $user->delete();
    }
}
