<?php

use yii\helpers\Html;

$this->title = 'Добавить семинар';
?>
<div class="seminar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cities'=>$cities,
        'pharmacies'=>$pharmacies,
        'seminar_cities' => $seminar_cities,
        'seminar_pharmacies' => $seminar_pharmacies
    ]) ?>

</div>
