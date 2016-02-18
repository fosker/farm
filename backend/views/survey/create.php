<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Survey */
/* @var $questions array */
/* @var $options array */

$this->title = 'Добавить анкету';
?>
<div class="survey-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'questions'=>$questions,
        'options'=>$options,
    ]) ?>

</div>
