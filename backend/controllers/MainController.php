<?php

namespace backend\controllers;

use Yii;

use backend\models\Param;

use yii\base\Model;
use yii\filters\AccessControl;

class MainController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'admin',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return $action->id == 'index' ? true : Yii::$app->admin->identity->can($action);
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionGeneral() {

        $settings = Param::find()->indexBy('id')->all();

        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {
            foreach ($settings as $setting) {
                $setting->save(false);
            }
            Yii::$app->session->setFlash('GeneralSettingsMessage', 'Данные успешно сохранены.');
        }

        return $this->render('general', ['settings' => $settings]);
    }

    public function actionIndex() {
        return $this->render('index');
    }

}