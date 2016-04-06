<?php

namespace common\models;

use Yii;
use common\models\news\Comment;
use common\models\news\View;
use yii\imagine\Image;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $thumbnail
 * @property string $date
 * @property integer $views_added
 */
class News extends \yii\db\ActiveRecord
{

    public $imageFile;
    public $thumbFile;

    public $views;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['views_added'], 'integer'],
            [['imageFile','thumbFile'], 'required', 'on' => 'create'],
            [['title', 'text', 'date'], 'string'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['title', 'text', 'imageFile', 'thumbFile'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'thumbnail' => 'Превью',
            'image' => 'Изображение',
            'imageFile' => 'Изображение',
            'thumbFile' => 'Превью',
            'date' => 'Дата публикации',
            'views' => 'Количество уникальных просмотров',
            'views_added' => 'Добавленные просмотры'
        ];
    }

    public function fields() {
        return [
            'id', 'title', 'thumb'=>'thumbPath',
        ];
    }

    public function extraFields() {
        return [
            'text',
            'views' => function () {
                return $this->countUniqueViews();
            },
            'image'=>'imagePath',
        ];
    }

    public static function getAllNews()
    {
        return static::find()
            ->orderBy(['id'=>SORT_DESC]);
    }

    public static function getOneForCurrentUser($id)
    {
        return static::find()->where(['id' => $id])->one();
    }

    public function countUniqueViews() {
        $this->views = View::find()->select('user_id')->
            distinct()->where(['news_id' => $this->id])->count() + $this->views_added;
        return $this->views;
    }

    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            $this->loadImage();
            $this->loadThumb();
            return true;
        } else return false;
    }

    public function afterDelete() {
        parent::afterDelete();
        Comment::deleteAll(['news_id'=>$this->id]);
        View::deleteAll(['news_id'=>$this->id]);
        if($this->image) @unlink(Yii::getAlias('@uploads/news/'.$this->image));
        if($this->thumbnail) @unlink(Yii::getAlias('@uploads/news/thumbs/'.$this->thumbnail));
    }

    public function loadImage() {
        if($this->imageFile) {
            $path = Yii::getAlias('@uploads/news/');
            if($this->image && file_exists($path . $this->image))
                @unlink($path . $this->image);
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->imageFile->extension;
            $path = $path . $filename;
            $this->imageFile->saveAs($path);
            $this->image = $filename;
            Image::thumbnail($path, 1000, 500)
                ->save(Yii::getAlias('@uploads/news/').$this->image, ['quality' => 80]);
        }
    }

    public function loadThumb() {
        if($this->thumbFile) {
            $path = Yii::getAlias('@uploads/news/thumbs/');
            if($this->thumbnail && file_exists($path . $this->thumbnail))
                @unlink($path . $this->thumbnail);
            $filename = Yii::$app->getSecurity()->generateRandomString() . time() . '.' . $this->thumbFile->extension;
            $path = $path . $filename;
            $this->thumbFile->saveAs($path);
            $this->thumbnail = $filename;
            Image::thumbnail($path, 200, 300)
                ->save(Yii::getAlias('@uploads/news/thumbs/').$this->thumbnail, ['quality' => 80]);
        }
    }

    public function getImagePath() {
        return Yii::getAlias('@uploads_view/news/'.$this->image);
    }

    public function getThumbPath() {
        return Yii::getAlias('@uploads_view/news/thumbs/'.$this->thumbnail);
    }
}
