<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Пользователь: '.$model->name;

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['index'],['class'=>'btn btn-info']) ?>
        <?= $model->status == User::STATUS_VERIFY ? Html::a('Верифицировать', ['accept', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'confirm' => 'Вы уверены, что хотите подтвердить пользователя?',
                'method' => 'post',
            ],
        ]) :
        Html::a('Забанить', ['ban', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => 'Вы уверены, что хотите забанить пользователя?',
                'method' => 'post',
            ],
        ]); ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'avatar',
                'value'=>Html::img($model->avatarPath, ['alt' => 'Аватар']),
                'format'=>'html',
            ],
            'login',
            'name',
            [
                'attribute'=>'sex',
                'value'=>$model->sex == User::SEX_MALE ? 'мужской' : 'женский',
            ],
            'email:email',
            'education.name',
            'pharmacy.city.name',
            'pharmacy.city.region.name',
            'pharmacy.firm.name',
            [
                'attribute'=>'pharmacy.name',
                'value'=>Html::a($model->pharmacy->name,['/pharmacy/view','id'=>$model->pharmacy_id]),
                'format'=>'html',
            ],
            'position.name',
            [
                'attribute'=>'status',
                'value'=> $model->status == User::STATUS_VERIFY ? 'Ожидает верификацию' : 'активный',
            ],
            'date_reg:datetime',
            'points',
        ],
    ]) ?>

</div>
