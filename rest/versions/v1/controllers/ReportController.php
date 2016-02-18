<?php

namespace rest\versions\v1\controllers;

use Yii;

use rest\components\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

use common\models\report\Product;
use common\models\Report;

class ReportController extends Controller
{

    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return new ActiveDataProvider([
            'query' => Report::getForCurrentUser(),
        ]);
    }

    public function actionFactory($report_id) {
        return Report::getOneForCurrentUser($report_id)->factory;
    }

    public function actionProducts($report_id) {
        return new ActiveDataProvider([
            'query' => Product::findByReport($report_id),
        ]);
    }

    public function actionProduct($id) {
        return Product::findOne($id);
    }

    public function actionSendReport() {
        $counts = [new Sent()];
        for($i = 1; $i < count($_REQUEST['item']); $i++) {
            $counts[] = new Sent();
        }

        if(Sent::loadMultiple($counts,$_REQUEST,'item') && Sent::validateMultiple($counts)) {
            Yii::$app->user->identity->sendReport(Report::findByItemId(reset($counts)->item_id));
            foreach($counts as $count) {
                $count->date_send = date("Y-m-d");
                $count->user_id = Yii::$app->user->id;
                $count->save(false);
            }
            return ['success'=>true];
        } else return $counts;
    }

}