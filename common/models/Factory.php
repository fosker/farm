<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use common\models\factory\Product;
use common\models\factory\Stock;

/**
 * This is the model class for table "factories".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $logo
 */
class Factory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories';
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

    public function fields() {
        return [
            'id','title','logo'=>'logoPath',
        ];
    }

    public function extraFields() {
        return [
            'description','image'=>'imagePath','products','stocks'
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(),['factory_id'=>'id']);
    }

    public function getImagePath()
    {
        return Yii::getAlias('@uploads_view/factories/'.$this->image);
    }

    public function getLogoPath()
    {
        return Yii::getAlias('@uploads_view/factories/logos/'.$this->logo);
    }

    public function getStocks()
    {
        return $this->hasMany(Stock::className(),['factory_id'=>'id']);
    }

    /**
     * @return \yii\db\Query
     */
    public static function getForCurrentUser()
    {
        return static::find()
            ->where(['id'=>Stock::getForCurrentUser()->select('factory_id')]);
    }

    /**
     * @return Factory|null
     */
    public static function getOneForCurrentUser($id)
    {
        return static::getForCurrentUser()->andWhere([static::tableName().'.id'=>$id])->one();
    }

}
