<?php

namespace common\models\factory;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use common\models\User;
use common\models\factory\Stock;

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
                    if (!Stock::getOneForCurrentUser($this->stock_id)) {
                        $this->addError('stock_id', 'Вы не можете участвовать в этой акции');
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

    public function getImagePath()
    {
        return Yii::getAlias('@uploads_view/stock-replies/'.$this->photo);
    }

    public function afterDelete()
    {
        if($this->photo)
            @unlink(Yii::getAlias('@uploads/stock-replies/'.$this->photo));
        parent::afterDelete();
    }

    public function saveImage()
    {

        if($this->image) {
            $path = Yii::getAlias('@uploads/stock-replies/');
            if($this->photo && file_exists($path . $this->photo))
                @unlink($path . $this->photo);
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->image->extension;
            $path = $path . $filename;
            $this->image->saveAs($path);
            $this->photo = $filename;
            Image::thumbnail($path, 200, 200)
                ->save(Yii::getAlias('@uploads/stock-replies/').$this->photo, ['quality' => 80]);
        }
    }

    public function fields() {
        return [
            'stock_id','image'
        ];
    }

    public function getUser() {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public function getStock() {
        return $this->hasOne(Stock::className(),['id'=>'stock_id']);
    }

}
