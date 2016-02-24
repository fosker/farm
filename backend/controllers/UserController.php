<?php

namespace backend\controllers;

use Yii;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\profile\Position;
use common\models\agency\Firm;
use common\models\agency\Pharmacy;
use common\models\User;
use common\models\location\City;
use backend\models\profile\Search;

class UserController extends Controller
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
        $searchModel = new Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'names' => ArrayHelper::map(User::find()->asArray()->all(), 'name','name'),
            'firms' => ArrayHelper::map(Firm::find()->asArray()->all(), 'id','name'),
            'cities' => ArrayHelper::map(City::find()->asArray()->all(), 'id','name'),
            'emails' => ArrayHelper::map(User::find()->asArray()->all(), 'email','email'),
            'pharmacies' => ArrayHelper::map(Pharmacy::find()->asArray()->all(), 'id','name'),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Пользователя не существует.');
        }
    }

    public function actionAccept($id) {
        $this->findModel($id)->verified();

        return $this->redirect(['index']);
    }

    public function actionBan($id) {
        $this->findModel($id)->ban();

        return $this->redirect(['index']);
    }

    public function actionPush() {

        $push_tokens = ['a9fcd33d25b6334608c8e51cb8ddd2e98b5c88d64d76be4fc185f0bef7a66383'];

        $message = 'HELLO FROM THE OTHER SIDE!';

        Yii::$app->apns->sendMulti($push_tokens, $message, [], [
            'sound' => 'default',
            'badge' => 1
        ]);

        Yii::$app->gcm->sendMulti($push_tokens, $message);

        return $this->render('push');
    }
}
