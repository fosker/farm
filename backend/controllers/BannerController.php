<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\Block;
use common\models\Item;
use common\models\location\City;
use common\models\agency\Pharmacy;
use common\models\banner\City as Banner_City;
use common\models\banner\Pharmacy as Banner_Pharmacy;
use common\models\profile\Education;
use common\models\Report;
use common\models\Seminar;
use common\models\Survey;
use common\models\Banner;
use common\models\agency\Firm;
use backend\models\banner\Search;

class BannerController extends Controller
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
            'positions' => Banner::positions(),
            'pages' => Banner::pages(),
            'education' => ArrayHelper::map(Education::find()->asArray()->all(),'id','name'),
            'firms' => ArrayHelper::map(Firm::find()->asArray()->all(),'id','name'),
            'cities'=>ArrayHelper::map(City::find()->asArray()->all(), 'id','name'),
            'titles'=>ArrayHelper::map(Banner::find()->asArray()->all(), 'title','title'),
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
        $model = new Banner();
        $banner_cities = new Banner_City();
        $banner_pharmacies = new Banner_Pharmacy();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'education' => ArrayHelper::map(Education::find()->asArray()->all(),'id','name'),
                'cities'=>City::find()->asArray()->all(),
                'pharmacies'=>Pharmacy::find()->asArray()->all(),
                'banner_cities' => $banner_cities,
                'banner_pharmacies' => $banner_pharmacies
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $banner_cities = new Banner_City();
        $banner_pharmacies = new Banner_Pharmacy();

        $old_cities = Banner_City::find()->select('city_id')->where(['banner_id' => $id])->asArray()->all();
        $old_pharmacies = Banner_Pharmacy::find()->select('pharmacy_id')->where(['banner_id' => $id])->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->save(false)) {
                $model->hide();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'education' => ArrayHelper::map(Education::find()->asArray()->all(),'id','name'),
                'cities'=>City::find()->asArray()->all(),
                'pharmacies'=>Pharmacy::find()->asArray()->all(),
                'banner_cities' => $banner_cities,
                'banner_pharmacies' => $banner_pharmacies,
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
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLinkList($q = null, $id = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $block = Block::find()->select('CONCAT("block/",`id`) as id, CONCAT("Страница: ",`title`) as text')->where(['like','CONCAT("Страница: ",title)',$q])->asArray()->limit(20);

            $survey = Survey::find()->select('CONCAT("survey/",`id`) as id, CONCAT("Анкета: ",`title`) as text')->where(['like','CONCAT("Анкета: ",title)',$q])->asArray();

            $seminar = Seminar::find()->select('CONCAT("seminar/",`id`) as id, CONCAT("Семинар: ",`title`) as text')->where(['like','CONCAT("Семинар: ",title)',$q])->asArray();

            $present = Item::find()->select('CONCAT("present/",`id`) as id, CONCAT("Подарок: ",`title`) as text')->where(['like','CONCAT("Подарок: ",title)',$q])->asArray();

            $report = Report::find()->select('CONCAT("report/",`id`) as id, CONCAT("Отчет: ",`title`) as text')->where(['like','CONCAT("Отчет: ",title)',$q])->asArray();
            $block->union($survey)->union($seminar)->union($present)->union($report);
            $out['results'] = array_values($block->limit(20)->all());
        }
        elseif (!is_null($id)) {
            $path = explode("/",$id);
            switch($path[0]) {
                case 'block':
                    $item = Block::findOne($path[1]);
                    break;
                case 'present':
                    $item = Item::findOne($path[1]);
                    break;
                case 'survey':
                    $item = Survey::findOne($path[1]);
                    break;
                case 'seminar':
                    $item = Seminar::findOne($path[1]);
                    break;
                case 'report':
                    $item = Report::findOne($path[1]);
                    break;
            }
            $out['results'] = ['id' => $id, 'text' => $item->title];
        }

        return $out;
    }

    public function actionTest() {
        var_dump( $block = Block::find()
            ->select('id, CONCAT("Блок: ",`title`) as text')
            ->where(['like','CONCAT("Блок: ",title)','Блок'])
            ->asArray()->limit(20)->all());
    }

    public function actionApprove($id)
    {
        $this->findModel($id)->approve();

        return $this->redirect(['index']);
    }

    public function actionHide($id)
    {
        $this->findModel($id)->hide();

        return $this->redirect(['index']);
    }
}
