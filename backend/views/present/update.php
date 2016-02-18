<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $vendors array */
/* @var $cities array */

$this->title = 'Редактировать подарок: ' . ' ' . $model->title;
?>
<div class="item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'vendors'=>$vendors,
        'cities'=>$cities,
    ]) ?>

</div>
