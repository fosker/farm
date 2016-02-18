<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->user->name." искал ".$model->substance->cyrillic;
?>
<div class="request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить запрос?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Пользователь',
                'attribute'=>'user.name',
                'value'=>Html::a($model->user->name,['/user/view','id'=>$model->user_id]),
                'format'=>'html',
            ],
            'user.education.name',
            'user.position.name',
            'substance.cyrillic',
            'substance.name',
            'date_request:datetime',
        ],
    ]) ?>

</div>
