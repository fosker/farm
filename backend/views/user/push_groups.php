<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use backend\components\CheckWidget;
use kartik\form\ActiveForm;
use common\models\location\Region;
use common\models\agency\Firm;
use kartik\widgets\Select2;
use common\models\location\City;
use common\models\agency\Pharmacy;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */

$this->title = 'Push-уведомления';
$this->registerJsFile('backend/web/js/checkWidget.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$city_list = \yii\helpers\Url::to(['city-list']);
$pharmacy_list = \yii\helpers\Url::to(['pharmacy-list']);
?>
<div class="user-push">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    $regions = Region::find()->asArray()->all();
    $firms = Firm::find()->asArray()->all();


    echo $form->field($model, 'message')->textInput();

    Modal::begin([
        'header' => '<h2>Выберите города</h2>',
        'toggleButton' => ['label' => 'Для городов', 'class' => 'btn btn-primary'],
    ]);

    echo $form->field($model, 'cities')->widget(CheckWidget::className(), [
        'parent_title' => 'regions',
        'parent' => $regions,

        'child_title' => 'cities',
        'child' => $cities,
        'relation' => 'region_id'
    ]);
    Modal::end();


    Modal::begin([
        'header' => '<h2>Выберите аптеки</h2>',
        'toggleButton' => ['label' => 'Для аптек', 'class' => 'btn btn-primary'],
    ]);
    echo $form->field($model, 'pharmacies')->widget(CheckWidget::className(), [
        'parent_title' => 'firms',
        'parent' => $firms,

        'child_title' => 'pharmacies',
        'child' => $pharmacies,
        'relation' => 'firm_id'

    ]);
    Modal::end();


    Modal::begin([
        'header' => '<h2>Выберите образования</h2>',
        'toggleButton' => ['label' => 'Для образований', 'class' => 'btn btn-primary'],
    ]);

    echo $form->field($model, 'education')->widget(CheckWidget::className(), [
        'parent_title' => 'education',
        'parent' => $education,
        'height' => '10px'
    ]);
    Modal::end();
    ?>

    <p></p>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
