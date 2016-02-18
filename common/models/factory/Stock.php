<?php

namespace common\models\factory;

use Yii;
use yii\db\ActiveRecord;

use common\models\Factory;

/**
 * This is the model class for table "factory_stocks".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property integer $status
 */
class Stock extends ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_HIDDEN = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_stocks';
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

    public function fields()
    {
        return [
            'id','image'=>'imagePath','title'
        ];
    }

    public function extraFields()
    {
        return [
            'description'
        ];
    }

    /**
     * @return \yii\db\Query
     */
    public static function getForCurrentUser()
    {
        return static::find()
            ->andWhere(['status'=>static::STATUS_ACTIVE])
            ->joinWith('cities')
            ->joinWith('pharmacies')
            ->andWhere([City::tableName().'.city_id'=>Yii::$app->user->identity->pharmacy->city_id])
            ->andWhere([Pharmacy::tableName().'.pharmacy_id'=>Yii::$app->user->identity->pharmacy_id])
            ->orderBy(['id'=>SORT_DESC]);
    }

    public static function getOneForCurrentUser($id)
    {
        return static::getForCurrentUser()->andWhere(['id'=>$id])->one();
    }

    public function getCities()
    {
        return $this->hasMany(City::className(),['stock_id'=>'id']);
    }

    public function getPharmacies()
    {
        return $this->hasMany(Pharmacy::className(),['stock_id'=>'id']);
    }

    public function getImagePath()
    {
        return Yii::getAlias('@uploads_view/stocks/'.$this->image);
    }

    public function getFactory()
    {
        return $this->hasOne(Factory::className(),['id'=>'factory_id']);
    }

}
