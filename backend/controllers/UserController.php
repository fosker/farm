<?php

namespace backend\controllers;

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

    public function actionAccept($id)
    {
        $this->findModel($id)->verified();

        return $this->redirect(['index']);
    }

    public function actionBan($id)
    {
        $this->findModel($id)->ban();

        return $this->redirect(['index']);
    }


    public function actionPushGroups()
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

            $android_tokens = array_filter(array_unique($android_tokens));
            $ios_tokens = array_filter(array_unique($ios_tokens));
            Yii::$app->apns->sendMulti($ios_tokens, $model->message, [], [
                'sound' => 'default',
                'badge' => 1
            ]);

            Yii::$app->gcm->sendMulti($android_tokens, $model->message);
            Yii::$app->session->setFlash('PushMessage', 'Push-уведомление успешно отправлено. ');
            return $this->redirect(['index']);
        } else {
            return $this->render('push_groups', [
                'model' => $model,
                'cities'=>City::find()->asArray()->all(),
                'pharmacies'=>Pharmacy::find()->asArray()->all(),
                'education' => Education::find()->asArray()->all()
            ]);
        }
    }
}
