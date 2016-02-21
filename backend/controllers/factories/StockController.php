<?php

namespace backend\controllers\factories;

use Yii;
use common\models\factory\Stock;
use backend\models\factory\stock\Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Factory;
use common\models\location\City;
use common\models\agency\Pharmacy;
use common\models\factory\City as Stock_City;
use common\models\factory\Pharmacy as Stock_Pharmacy;
use common\models\agency\Firm;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class StockController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
            'factories'=> ArrayHelper::map(Factory::find()->asArray()->all(),'id','title'),
            'firms' => ArrayHelper::map(Firm::find()->asArray()->all(),'id','name'),
            'cities'=>ArrayHelper::map(City::find()->asArray()->all(), 'id','name'),
            'titles'=>ArrayHelper::map(Stock::find()->asArray()->all(), 'title','title'),
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Stock();
        $stock_cities = new Stock_City();
        $stock_pharmacies = new Stock_Pharmacy();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'factories'=> ArrayHelper::map(Factory::find()->asArray()->all(),'id','title'),
                'cities'=>City::find()->asArray()->all(),
                'pharmacies'=>Pharmacy::find()->asArray()->all(),
                'stock_cities' => $stock_cities,
                'stock_pharmacies' => $stock_pharmacies
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $stock_cities = new Stock_City();
        $stock_pharmacies = new Stock_Pharmacy();

        $old_cities = Stock_City::find()->select('city_id')->where(['stock_id' => $id])->asArray()->all();
        $old_pharmacies = Stock_Pharmacy::find()->select('pharmacy_id')->where(['stock_id' => $id])->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'factories'=> ArrayHelper::map(Factory::find()->asArray()->all(),'id','title'),
                'cities'=>City::find()->asArray()->all(),
                'pharmacies'=>Pharmacy::find()->asArray()->all(),
                'stock_cities' => $stock_cities,
                'stock_pharmacies' => $stock_pharmacies,
                'old_cities' => $old_cities,
                'old_pharmacies' => $old_pharmacies
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Stock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }
}
