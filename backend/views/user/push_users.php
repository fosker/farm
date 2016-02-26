<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use backend\components\CheckWidget;
use kartik\form\ActiveForm;
use common\models\location\Region;
use common\models\agency\Firm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */

$this->title = 'Push-уведомления для пользователей';

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
