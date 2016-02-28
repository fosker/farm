<?php

namespace backend\controllers\users;

use Yii;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Push;
use common\models\User;
use common\models\profile\Device;


class PushUsersController extends Controller
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

        if($model->load(Yii::$app->request->post())) {

            $ids = Yii::$app->request->post('Push')['users'] ? Yii::$app->request->post('Push')['users'] : [];

            $android_tokens = ArrayHelper::map(Device::find()->select('id, push_token')->where(['in', 'user_id', $ids])
                ->andWhere(['not',['push_token' => null]])
                ->andWhere(['type' => 1])
                ->asArray()
                ->all(), 'id', 'push_token');

            $android_tokens = array_values($android_tokens);
            $android_tokens = array_filter(array_unique($android_tokens));

            $ios_tokens = ArrayHelper::map(Device::find()->select('id, push_token')->where(['in', 'user_id', $ids])
                ->andWhere(['not',['push_token' => null]])
                ->andWhere(['type' => 2])
                ->asArray()
                ->all(), 'id', 'push_token');

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
                $test = ['cDM3Ye5umUM:APA91bEUDMs_bQ1BR4pD1aCG-uPRCfHsO99yLaH2-LRiBmwFY_EcvqNbZ8s7dAONwgchKIk80u7j5pJdp29dDPWrb8EcV4tA9uJvlv4BgitZet89QUH0t3jZzy8NOomwJUPyfAhaL_c-'];
                if(Yii::$app->gcm->sendMulti($android_tokens, $model->message)){
                    Yii::$app->session->setFlash('PushMessage2',
                        'Push-уведомление успешно отправлено на ' . count($android_tokens) . ' android-устройств');
                }
            }

            return $this->redirect(['index']);

        } else {
            return $this->render('index', [
                'model' => $model,
                'users' => ArrayHelper::map(User::find()
                    ->select(['id', new \yii\db\Expression("CONCAT(`name`, ' (', `login`,')') as login")])
                    ->asArray()
                    ->all(), 'id','login'),
            ]);
        }
    }

}
