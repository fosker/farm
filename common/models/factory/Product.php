<?php

namespace common\models\factory;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "factory_products".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $thumbnail
 * @property integer $factory_id
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_products';
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
            'id','title','thumbnail'=>'thumbnailPath'
        ];
    }

    public function extraFields() {
        return [
            'description','image'=>'imagePath'
        ];
    }

    public function getImagePath()
    {
        return Yii::getAlias('@uploads_view/factories/products/'.$this->image);
    }

    public function getThumbnailPath()
    {
        return Yii::getAlias('@uploads_view/factories/products/thumbnail/'.$this->thumbnail);
    }

}
