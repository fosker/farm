<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

$this->title = 'Подарки';
?>
<div class="present-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Логин',
                'attribute'=>'user.login',
                'value'=>function($model) {
                    return Html::a($model->user->login, ['/user/view', 'id'=>$model->user->login]);
                },
                'format'=>'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $logins,
                    'attribute'=>'user.login',
                    'options' => [
                        'placeholder' => 'Выберите логин ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '300px'
                    ],
                ])
            ],
            [
                'label' => 'Название подарка',
                'attribute'=>'item.title',
                'value'=>function($model) {
                    return Html::a($model->item->title, ['/present/view', 'id'=>$model->item->id]);
                },
                'format'=>'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $items,
                    'attribute'=>'item.title',
                    'options' => [
                        'placeholder' => 'Выберите подарок ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '300px'
                    ],
                ])
            ],
            'count',
            'promo',
            'date_buy:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {use} {delete}',
                'buttons'=>[
                    'use'=>function ($url, $model, $key) {
                        return $model->promo ? Html::a('<i class="glyphicon glyphicon-gift"></i>', [ 'use', 'id'=>$model->id],
                            ['title'=>'Использовать']) : '';
                    },
                ],
            ],
        ],
    ]); ?>

</div>
