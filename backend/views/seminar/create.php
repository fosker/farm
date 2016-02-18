<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Seminar */

$this->title = 'Добавить семинар';
?>
<div class="seminar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
