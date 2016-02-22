<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Survey */

$this->title = 'Редактирование данных: ' . ' ' . $model->title;
?>
<div class="survey-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'questions'=>$questions,
        'options'=>$options,
        'cities'=>$cities,
        'pharmacies'=>$pharmacies,
        'survey_cities' => $survey_cities,
        'survey_pharmacies' => $survey_pharmacies,
        'old_cities' => $old_cities,
        'old_pharmacies' => $old_pharmacies
    ]) ?>

</div>
