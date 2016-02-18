<?php

namespace common\models\survey;

use Yii;

/**
 * This is the model class for table "survey_for_pharmacies".
 *
 * @property integer $pharmacy_id
 * @property integer $survey_id
 */
class Pharmacy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_for_pharmacies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

        ];
    }

    public function getPharmacy() {
        return $this->hasOne(\common\models\agency\Pharmacy::className(),['id'=>'pharmacy_id']);
    }
}
