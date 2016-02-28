<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\Growl;
$this->title = 'Push-уведомления для пользователей';

if(Yii::$app->session->hasFlash('PushMessage')) :
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Успешно',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('PushMessage'),
        'showSeparator' => true,
        'delay' => 0,
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ]);
endif;

if(Yii::$app->session->hasFlash('PushMessage2')) :
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Успешно',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('PushMessage2'),
        'showSeparator' => true,
        'delay' => 0,
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ]);
endif;
?>
<div class="user-push">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?=  $form->field($model, 'message')->textInput(); ?>

    <?= $form->field($model, 'users')->widget(Select2::classname(), [
        'data' => $users,
        'options' => [
            'placeholder' => 'Выберите пользователей ...',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <p></p>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
