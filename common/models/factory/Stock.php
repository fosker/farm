<?php

namespace common\models\factory;

use Yii;
use yii\db\ActiveRecord;

use common\models\Factory;
use common\models\location\City as Region_City;
use common\models\agency\Pharmacy as P;
use common\models\agency\Firm;
use common\models\factory\City;
use common\models\factory\Pharmacy;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

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

    public $imageFile;

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
            [['title', 'factory_id'], 'required'],
            [['description', 'title'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Фабрика',
            'title' => 'Название акции',
            'description' => 'Описание',
            'image' => 'Изображение',
            'imageFile' => 'Изображение',
            'status' => 'Статус'
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
            ->orderBy(['id'=>SORT_DESC])
            ->groupBy(static::tableName().'.id');
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

    public static function getStatusList()
    {
        return [static::STATUS_ACTIVE=>'активный',static::STATUS_HIDDEN=>'скрытый'];
    }

    public function getCitiesView($isFull = false)
    {
        $result = ArrayHelper::getColumn((City::find()
            ->select(Region_City::tableName().'.name')
            ->joinWith('city')
            ->asArray()
            ->where(['stock_id'=>$this->id])
            ->all()),'name');
        $string = "";
        if(!$isFull) {
            $limit = 5;
            if (count($result) > $limit) {
                for ($i = 0; $i < $limit; $i++) {
                    $string .= $result[$i].", ";
                }
                $string .= "и ещё (".(count($result)-$limit).")";
            } else
                $string = implode(", ", $result);
        } else
            $string = implode(", ", $result);

        return $string;
    }

    public function getFirmsView($isFull = false) {
        $result = ArrayHelper::getColumn((Firm::find()->select([
            'firms.name'])
            ->from(Firm::tableName())
            ->join('LEFT JOIN', P::tableName(),
                Firm::tableName().'.id = '.P::tableName().'.firm_id')
            ->join('LEFT JOIN', Pharmacy::tableName(),
                Pharmacy::tableName().'.pharmacy_id = '.P::tableName().'.id')
            ->distinct()
            ->asArray()
            ->where(['stock_id' => $this->id])
            ->all()),'name');
        $string = "";
        if(!$isFull) {
            $limit = 5;
            if (count($result) > $limit) {
                for ($i = 0; $i < $limit; $i++) {
                    $string .= $result[$i].", ";
                }
                $string .= "и ещё (".(count($result)-$limit).")";
            } else
                $string = implode(", ", $result);
        } else
            $string = implode(", ", $result);

        return $string;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            City::deleteAll(['stock_id' => $this->id]);
            Pharmacy::deleteAll(['stock_id' => $this->id]);
        }
        for ($i = 0; $i < count(Yii::$app->request->post('cities')); $i++) {
            $city = new City();
            $city->city_id = Yii::$app->request->post('cities')[$i];
            $city->stock_id = $this->id;
            $city->save();
        }
        for ($i = 0; $i < count(Yii::$app->request->post('pharmacies')); $i++) {
            $pharmacies = new Pharmacy();
            $pharmacies->pharmacy_id = Yii::$app->request->post('pharmacies')[$i];
            $pharmacies->stock_id = $this->id;
            $pharmacies->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->loadImage();
            return true;
        } else return false;
    }

    public function afterDelete()
    {
        if($this->image) @unlink(Yii::getAlias('@uploads/stocks/'.$this->image));
        City::deleteAll(['stock_id'=>$this->id]);
        Pharmacy::deleteAll(['stock_id'=>$this->id]);
        parent::afterDelete();
    }

    public function loadImage()
    {
        if($this->imageFile) {
            $path = Yii::getAlias('@uploads/stocks/');
            if($this->image && file_exists($path . $this->image))
                @unlink($path . $this->image);
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->imageFile->extension;
            $path = $path . $filename;
            $this->imageFile->saveAs($path);
            $this->image = $filename;
            Image::thumbnail($path, 1000, 500)
                ->save(Yii::getAlias('@uploads/stocks/').$this->image, ['quality' => 80]);
        }
    }

}
