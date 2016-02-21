<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Presentation */

$this->title = 'Редактирование данных: ' . ' ' . $model->title;
?>
<div class="presentation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cities'=>$cities,
        'pharmacies'=>$pharmacies,
        'presentation_cities' => $presentation_cities,
        'presentation_pharmacies' => $presentation_pharmacies,
        'old_cities' => $old_cities,
        'old_pharmacies' => $old_pharmacies
    ]) ?>

</div>
