<?php

use kartik\file\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\location\Region;
use common\models\agency\Firm;
use backend\components\CheckWidget;
use yii\bootstrap\Modal;

$url = Url::to(['/banner/link-list']);
?>

<div class="banner-form">

    <? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?
        $regions = Region::find()->asArray()->all();
        $firms = Firm::find()->asArray()->all();

        Modal::begin([
            'header' => '<h2>Выберите города</h2>',
            'toggleButton' => ['label' => 'Для городов', 'class' => 'btn btn-primary'],
        ]);

        echo $form->field($banner_cities, 'cities')->widget(CheckWidget::className(), [
            'parent_title' => 'regions',
            'parent' => $regions,
            'update' => $old_cities,

            'child_title' => 'cities',
            'child' => $cities,
            'relation' => 'region_id'
        ]);
        Modal::end();


        Modal::begin([
            'header' => '<h2>Выберите аптеки</h2>',
            'toggleButton' => ['label' => 'Для аптек', 'class' => 'btn btn-primary'],
        ]);
            echo $form->field($banner_pharmacies, 'pharmacies')->widget(CheckWidget::className(), [
                'parent_title' => 'firms',
                'parent' => $firms,
                'update' => $old_pharmacies,

                'child_title' => 'pharmacies',
                'child' => $pharmacies,
                'relation' => 'firm_id'

            ]);
        Modal::end();
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->dropDownList($model::positions(), ['prompt'=>'']) ?>

    <?= $form->field($model, 'link')->widget(Select2::classname(),
        [
            'initValueText' => $model->linkTitle,
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(link) { return link.text; }'),
                'templateSelection' => new JsExpression('function (link) { return link.text; }'),
            ],
        ]
    ); ?>

    <?= $form->field($model, 'imageFile')->widget(FileInput::classname(),[
        'pluginOptions' => [
            'initialPreview'=> $model->image ? Html::img($model->imagePath, ['class'=>'file-preview-image', 'alt'=>'image', 'title'=>'Image']) : '',
            'showUpload' => false,
            'showRemove' => false,
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
