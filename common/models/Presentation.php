<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use common\models\presentation\View;
use common\models\presentation\City;
use common\models\presentation\Pharmacy;
use common\models\presentation\Question;
use common\models\presentation\Slide;
use common\models\location\City as C;
use common\models\agency\Pharmacy as P;
use common\models\agency\Firm;

/**
 * This is the model class for table "presentations".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $points
 * @property string $image
 * @property string $thumbnail
 * @property integer $status
 * @property integer home
 * @property integer home_priority
 */
class Presentation extends ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_HIDDEN = 0;

    public $imageFile;
    public $thumbFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'presentations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'points'], 'required'],
            [['description'], 'string'],
            ['points', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название презентации',
            'description' => 'Описание',
            'points' => 'Баллы',
            'imageFile' => 'Изображение',
            'thumbFile' => 'Превью',
            'image' => 'Изображение',
            'thumbnail' => 'Превью',
            'status' => 'Статус',
        ];
    }

    public function fields()
    {
        return [
            'id', 'title', 'points', 'thumb'=>'thumbPath',
        ];
    }

    public function extraFields()
    {
        return [
            'description', 'questions', 'image'=>'imagePath', 'slides','viewed'=>function($model) {
                return static::isViewedByCurrentUser($model->id);
            }
        ];
    }

    /**
     * @return \yii\db\Query
     */
    public static function getForCurrentUser()
    {
        return static::find()
            ->joinWith('cities')
            ->joinWith('pharmacies')
            ->andWhere([City::tableName().'.city_id'=>Yii::$app->user->identity->pharmacy->city_id])
            ->andWhere([Pharmacy::tableName().'.pharmacy_id'=>Yii::$app->user->identity->pharmacy_id])
            ->andWhere(['status'=>static::STATUS_ACTIVE])
            ->orderBy(['id'=>SORT_DESC])
            ->groupBy(static::tableName().'.id');
    }

    public static function getNotViewedForCurrentUser() {
        return static::getForCurrentUser()
            ->andWhere([
                'not exists',
                View::findByCurrentUser()
                    ->andWhere(View::tableName().'.presentation_id='.static::tableName().'.id')
            ]);
    }

    public static function getViewedForCurrentUser() {
        return static::getForCurrentUser()
            ->andWhere([
                'exists',
                View::findByCurrentUser()
                    ->andWhere(View::tableName().'.presentation_id='.static::tableName().'.id')
            ]);
    }

    public static function getOneForCurrentUser($id)
    {
        return static::getForCurrentUser()->andWhere([static::tableName().'.id'=>$id])->one();
    }

    public function getQuestions() {
        return Question::find()->select('*')->from(Question::tableName())->where(['presentation_id' => $this->id])->orderBy('order_index')->all();
    }

    public function getSlides() {
        return Slide::find()->select('*')->from(Slide::tableName())->where(['presentation_id' => $this->id])->orderBy('order_index')->all();
    }

    public function getCities() {
        return $this->hasMany(City::className(), ['presentation_id' => 'id']);
    }

    public function getPharmacies() {
        return $this->hasMany(Pharmacy::className(), ['presentation_id' => 'id']);
    }

    public function getViews() {
        return $this->hasMany(View::className(), ['presentation_id' => 'id']);
    }

    public function getImagePath() {
        return Yii::getAlias('@uploads_view/presentations/'.$this->image);
    }

    public function getThumbPath() {
        return Yii::getAlias('@uploads_view/presentations/thumbs/'.$this->thumbnail);
    }

    public static function isViewedByCurrentUser($id)
    {
        return View::findByCurrentUser()->andWhere(['presentation_id'=>$id])->exists();
    }

    public function approve()
    {
        $this->status = static::STATUS_ACTIVE;
        $this->save(false);
    }

    public function hide()
    {
        $this->status = static::STATUS_HIDDEN;
        $this->save(false);
    }

    public function getAnswersCount()
    {
        return View::find()
            ->where(['presentation_id'=>$this->id])
            ->count();
    }

    public static function getStatusList()
    {
        return [static::STATUS_ACTIVE=>'активный',static::STATUS_HIDDEN=>'скрытый'];
    }

    public function getCitiesView($isFull = false)
    {
        $result = ArrayHelper::getColumn((City::find()
            ->select(C::tableName().'.name')
            ->joinWith('city')
            ->asArray()
            ->where(['presentation_id'=>$this->id])
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
            ->where(['presentation_id' => $this->id])
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

    public function loadImage() {
        if($this->imageFile) {
            $path = Yii::getAlias('@uploads/presentations/');
            if ($this->image && file_exists($path . $this->image))
                @unlink($path . $this->image);
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->imageFile->extension;
            $path = $path . $filename;
            $this->imageFile->saveAs($path);
            $this->image = $filename;
            Image::thumbnail($path, 1000, 500)
                ->save(Yii::getAlias('@uploads/presentations/') . $this->image, ['quality' => 80]);
        }
    }

    public function loadThumb() {
        if($this->thumbFile) {
            $path = Yii::getAlias('@uploads/presentations/thumbs/');
            if ($this->thumbnail && file_exists($path . $this->thumbnail))
                @unlink($path . $this->thumbnail);
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->thumbFile->extension;
            $path = $path . $filename;
            $this->thumbFile->saveAs($path);
            $this->thumbnail = $filename;
            Image::thumbnail($path, 200, 300)
                ->save(Yii::getAlias('@uploads/presentations/thumbs/') . $this->thumbnail, ['quality' => 80]);
        }
    }

    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            $this->loadImage();
            $this->loadThumb();
            return true;
        } else return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            City::deleteAll(['presentation_id'=>$this->id]);
            Pharmacy::deleteAll(['presentation_id'=>$this->id]);
        }
        for ($i = 0; $i < count(Yii::$app->request->post('cities')); $i++) {
            $city = new City();
            $city->city_id = Yii::$app->request->post('cities')[$i];
            $city->presentation_id = $this->id;
            $city->save();
        }
        for ($i = 0; $i < count(Yii::$app->request->post('pharmacies')); $i++) {
            $pharmacies = new Pharmacy();
            $pharmacies->pharmacy_id = Yii::$app->request->post('pharmacies')[$i];
            $pharmacies->presentation_id = $this->id;
            $pharmacies->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        foreach($this->questions as $question)
            $question->delete();
        City::deleteAll(['presentation_id'=>$this->id]);
        Pharmacy::deleteAll(['presentation_id'=>$this->id]);
        Slide::deleteAll(['presentation_id'=>$this->id]);
        if($this->image) @unlink(Yii::getAlias('@uploads/presentations/'.$this->image));
        if($this->thumbnail) @unlink(Yii::getAlias('@uploads/presentations/thumbs/'.$this->thumbnail));
        parent::afterDelete();
    }
}
