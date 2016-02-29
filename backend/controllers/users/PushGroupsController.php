<?php

namespace backend\controllers\users;

use common\models\profile\Position;
use Yii;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Push;
use common\models\agency\Firm;
use common\models\agency\Pharmacy;
use common\models\User;
use common\models\location\City;
use backend\models\profile\Search;
use common\models\profile\Device;
use common\models\profile\Education;
use common\models\profile\UpdateRequest;
use common\models\location\Region;
use yii\helpers\Json;


class PushGroupsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'admin',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->admin->identity->can($action);
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Push();

        if(Yii::$app->request->post()) {
            $cities = Yii::$app->request->post('cities') ?  Yii::$app->request->post('cities') : [];
            $educations = Yii::$app->request->post('education') ?  Yii::$app->request->post('education') : [];
            $pharmacies = Yii::$app->request->post('pharmacies') ?  Yii::$app->request->post('pharmacies') : [];
            $model->load(Yii::$app->request->post());

            $users = ArrayHelper::map(User::find()->select(User::tableName().'.id')->andWhere(['in', 'education_id', $educations])
                ->andWhere(['in', 'pharmacy_id', $pharmacies])
                ->join('LEFT JOIN', Pharmacy::tableName(),
                    User::tableName().'.pharmacy_id = '.Pharmacy::tableName().'.id')
                ->andWhere(['in', 'city_id', $cities])
                ->asArray()
                ->all(), 'id', 'id');

            $android_tokens = ArrayHelper::map(Device::find()->select('id, push_token')->where(['in', 'user_id', $users])
                ->andWhere(['not',['push_token' => null]])
                ->andWhere(['type' => 1])
                ->asArray()
                ->all(), 'id', 'push_token');

            $ios_tokens = ArrayHelper::map(Device::find()->select('id, push_token')->where(['in', 'user_id', $users])
                ->andWhere(['not',['push_token' => null]])
                ->andWhere(['type' => 2])
                ->asArray()
                ->all(), 'id', 'push_token');

            $android_tokens = array_values($android_tokens);
            $android_tokens = array_filter(array_unique($android_tokens));

            $ios_tokens = array_values($ios_tokens);
            $ios_tokens = array_filter(array_unique($ios_tokens));

            if($ios_tokens)
            {
                if(Yii::$app->apns->sendMulti($ios_tokens, $model->message, [], [
                    'sound' => 'default',
                    'badge' => 1
                ])){
                    Yii::$app->session->setFlash('PushMessage',
                        'Push-уведомление успешно отправлено на '.count($ios_tokens).' ios-устройств');
                }
            }
            if($android_tokens)
            {
                if(Yii::$app->gcm->sendMulti($android_tokens, $model->message)){
                    Yii::$app->session->setFlash('PushMessage2',
                        'Push-уведомление успешно отправлено на ' . count($android_tokens) . ' android-устройств');
                }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'model' => $model,
                'cities'=>City::find()->asArray()->all(),
                'pharmacies'=>Pharmacy::find()->asArray()->all(),
                'education' => Education::find()->asArray()->all()
            ]);
        }
    }

}