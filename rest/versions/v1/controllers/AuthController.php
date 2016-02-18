<?php
namespace rest\versions\v1\controllers;

use Yii;
use yii\rest\Controller;

use backend\models\Param;
use common\models\profile\Device;
use common\models\User;
use common\models\profile\LoginForm;

class AuthController extends Controller
{

    public function actionOptions()
    {
        Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', ['GET', 'POST', 'PUT' ,'HEAD', 'OPTIONS','PATCH']));
    }

    public function actionRegisterDevice() {
        $device = new Device();

        if($device->load(Yii::$app->request->post(),'') && $device->save()) {
            return ['device_id'=>$device->id];
        } else return $device;
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(),'') && $model->login()) {
            return ['access_token'=>Yii::$app->user->identity->getAccessTokenByDevice($model->device_id)];
        }
        else return $model;
    }

    public function actionJoin()
    {
        $model = new User(['scenario'=>'join']);

        if ($model->load(Yii::$app->request->post(),'') && $model->validate()) {
            $model->register();
            return ['success'=>true];
        } else return $model;
    }

    public function actionResetPassword()
    {
        $model = new User(['scenario'=>'reset-password']);

        if($model->load(Yii::$app->getRequest()->getBodyParams(),'') && $model->validate()) {
            return ['success'=>$model->resetPassword()];
        } else return $model;
    }

    public function actionSendResetToken()
    {
     
        if(!$user = User::FindByEmail(Yii::$app->getRequest()->getBodyParams()['email']))
            throw new \yii\web\NotFoundHttpException('Пользователь с такой почтой не существует.');

        $user->generatePasswordResetToken();
        $user->save(false);

        Yii::$app->mailer->compose('@common/mail/repair-user-code', [
            'token'=>$user->reset_token,
        ])
            ->setFrom(Param::getParam('email'))
            ->setTo($user->email)
            ->setSubject("Восстановление доступа")
            ->send();
        return ['success'=>true];
    }

    public function actionCheckResetToken($reset_token)
    {
        return ['valid'=>(bool)User::findByPasswordResetToken($reset_token)];
    }

}