<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\presentation\Question */

$this->title = 'Редактирование данных: ' . ' ' . $model->question;
?>
<div class="question-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
