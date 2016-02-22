<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

$this->title = 'Ответы на акции';
?>
<div class="reply-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'ID Акции',
                'attribute'=>'stock_id',
                'value'=>'stock_id',
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            [
                'attribute'=>'user.login',
                'value'=>function($model) {
                    return Html::a($model->user->login, ['/user/view', 'id'=>$model->user->id]);
                },
                'format' => 'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $logins,
                    'attribute'=>'user.login',
                    'options' => [
                        'placeholder' => 'Выберите логин пользователя ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '350px'
                    ],
                ]),
            ],
            [
                'attribute'=>'stock.title',
                'value'=>function($model) {
                    return Html::a($model->stock->title, ['/stock/view', 'id'=>$model->stock->id]);
                },
                'format' => 'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $stocks,
                    'attribute'=>'stock.title',
                    'options' => [
                        'placeholder' => 'Выберите акцию ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '350px'
                    ],
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
            ],
        ],
    ]); ?>
</div>
