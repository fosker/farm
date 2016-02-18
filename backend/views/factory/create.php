<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Factory */

$this->title = 'Добавить фабрику';
?>
<div class="factory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
