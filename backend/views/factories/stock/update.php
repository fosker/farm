<?php

use yii\helpers\Html;


$this->title = 'Редактирование данных: ' . ' ' . $model->title;

?>
<div class="stock-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'factories' => $factories,
        'cities'=>$cities,
        'pharmacies'=>$pharmacies,
        'stock_cities' => $stock_cities,
        'stock_pharmacies' => $stock_pharmacies,
        'old_cities' => $old_cities,
        'old_pharmacies' => $old_pharmacies
    ]) ?>

</div>
