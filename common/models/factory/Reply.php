<?php

namespace common\models\factory;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * This is the model class for table "factory_stock_replies".
 *
 * @property integer $id
 * @property integer $stock_id
 * @property integer $user_id
 * @property string $photo
 */
class Reply extends ActiveRecord
{

    public $image;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_stock_replies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stock_id', 'user_id', 'image'], 'required'],
            [['stock_id'],function($model,$attr) {
                if (!$this->hasErrors()) {
                    if (Stock::getOneForCurrentUser($this->$attr)) {
                        $this->addError($attr, 'Вы нем ожете учавствовать в этой акции');
                    }
                }
            }],
            [['image'],'image',
                'extensions' => 'jpg',
                'minWidth' => 200, 'maxWidth' => 4000,
                'minHeight' => 200, 'maxHeight' => 4000,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stock_id' => 'Акция',
            'user_id' => 'Пользователь',
            'photo' => 'Фото',
        ];
    }

    public function saveImage()
    {
        if($this->image) {
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->image->extension;
            $path = Yii::getAlias('@uploads/stock-replies/'.$filename);
            $this->image->saveAs($path);
            $this->photo = $filename;
            Image::thumbnail($path, 200, 200)
                ->save($path, ['quality' => 100]);
        }
    }

    public function fields() {
        return [
            'stock_id','image'
        ];
    }

}
