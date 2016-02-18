<?php

namespace common\models\presentation;

use common\models\Presentation;
use Yii;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * This is the model class for table "presentation_views".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $presentation_id
 * @property string $added
 */
class View extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'presentation_views';
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
            'added' => 'Дата добавления',

        ];
    }

    /**
     * @return \yii\db\Query
     */
    public static function findByCurrentUser()
    {
        return static::find()->where(['user_id'=>Yii::$app->user->id]);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public function getPresentation() {
        return $this->hasOne(Presentation::className(),['id'=>'presentation_id']);
    }

    public static function addByCurrentUser($answers)
    {
        $view = new static();
        $view->user_id = Yii::$app->user->id;
        $view->presentation_id = $answers[0]->question->presentation_id;
        $view->save(false);
        foreach($answers as $answer) {
            $answer->view_id = $view->id;
            $answer->save(false);
        }
    }
}
