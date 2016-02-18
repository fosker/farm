<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\presentation\question\Option */

$this->title = 'Редактирование данных: ' . ' ' . $model->value;
?>
<div class="option-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
