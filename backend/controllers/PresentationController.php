<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use common\models\presentation\Question;
use common\models\presentation\Slide;
use common\models\presentation\Option;
use common\models\Presentation;
use common\models\agency\Firm;
use common\models\location\City;
use backend\models\presentation\Search;


class PresentationController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-question'=>['POST'],
                    'delete-slide'=>['POST'],
                    'delete-option'=>['POST'],
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
            'firms' => ArrayHelper::map(Firm::find()->asArray()->all(),'id','name'),
            'cities' => ArrayHelper::map(City::find()->asArray()->all(), 'id','name'),
            'titles' =>ArrayHelper::map(Presentation::find()->asArray()->all(), 'title','title'),
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
        $model = new Presentation();

        if($model->load(Yii::$app->request->getBodyParams())) {
            $model->imageFile = UploadedFile::getInstance($model,'imageFile');
            $model->thumbFile = UploadedFile::getInstance($model,'thumbFile');
            if($model->save(false)) {
                return $this->redirect(['view','id'=>$model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->getBodyParams())) {
            $model->imageFile = UploadedFile::getInstance($model,'imageFile');
            $model->thumbFile = UploadedFile::getInstance($model,'thumbFile');
            if($model->save())
            {
                return $this->redirect(['view','id'=>$model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Presentation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('�������� �� �������.');
        }
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

    public function actionAddSlide($presentation_id) {
        $model = new Slide();

        if($model->load(Yii::$app->request->getBodyParams())) {
            $model->imageFile = UploadedFile::getInstance($model,'imageFile');
            $model->presentation_id = $presentation_id;
            if($model->validate()) {
                $model->loadImage();
                $model->save(false);
                return $this->redirect(['view','id'=>$presentation_id]);
            }
        }

        return $this->render('slide/create', [
            'model'=>$model
        ]);
    }

    public function actionEditSlide($id) {
        $model = $this->findSlideModel($id);

        if($model->load(Yii::$app->request->getBodyParams())) {
            $model->imageFile = UploadedFile::getInstance($model,'imageFile');
            if($model->validate()) {
                $model->loadImage();
                $model->save(false);
                return $this->redirect(['view','id'=>$model->presentation_id]);
            }
        }

        return $this->render('slide/update', [
            'model'=>$model
        ]);
    }

    public function actionDeleteSlide($id) {
        $model = $this->findSlideModel($id);
        $model->delete();
        return $this->redirect(['view','id'=>$model->presentation_id]);
    }

    public function findSlideModel($id) {
        if (($model = Slide::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('�������� �� �������.');
        }
    }

    public function actionAddQuestion($presentation_id) {
        $model = new Question();

        if($model->load(Yii::$app->request->getBodyParams())) {
            $model->presentation_id = $presentation_id;
            if($model->save())
                return $this->redirect(['view','id'=>$presentation_id]);
        }

        return $this->render('question/create', [
            'model'=>$model
        ]);
    }

    public function actionEditQuestion($id) {
        $model = $this->findQuestionModel($id);

        if($model->load(Yii::$app->request->getBodyParams()) && $model->save()) {
            return $this->redirect(['view','id'=>$model->presentation_id]);
        }

        return $this->render('question/update', [
            'model'=>$model
        ]);
    }

    public function actionDeleteQuestion($id) {
        $model = $this->findQuestionModel($id);
        $model->delete();
        return $this->redirect(['view','id'=>$model->presentation_id]);
    }

    public function findQuestionModel($id) {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('�������� �� �������.');
        }
    }

    public function actionViewOption($question_id) {
        return $this->render('question/option/index', [
            'options'=>Option::findAllByQuestionId($question_id)->all(),
        ]);
    }

    public function actionAddOption($question_id) {
        $model = new Option();

        if($model->load(Yii::$app->request->getBodyParams())) {
            $model->question_id = $question_id;
            if($model->save())
                return $this->redirect(['view-option','question_id'=>$question_id]);
        }

        return $this->render('question/option/create', [
            'model'=>$model
        ]);
    }

    public function actionEditOption($id) {
        $model = $this->findOptionModel($id);

        if($model->load(Yii::$app->request->getBodyParams()) && $model->save()) {
            return $this->redirect(['view-option','question_id'=>$model->question_id]);
        }

        return $this->render('question/option/update', [
            'model'=>$model
        ]);
    }

    public function actionDeleteOption($id) {
        $model = $this->findOptionModel($id);
        $model->delete();
        return $this->redirect(['view-option','question_id'=>$model->question_id]);
    }

    public function findOptionModel($id) {
        if (($model = Option::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('�������� �� �������.');
        }
    }

}
