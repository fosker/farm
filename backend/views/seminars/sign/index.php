<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

$this->title = 'Записи на семинары';
?>
<div class="sign-up-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'ID Семинара',
                'attribute'=>'seminar_id',
                'value'=>'seminar_id',
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
                'attribute'=>'seminar.title',
                'value'=>function($model) {
                    return Html::a($model->seminar->title, ['/seminar/view', 'id'=>$model->seminar->id]);
                },
                'format' => 'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $seminars,
                    'attribute'=>'seminar.title',
                    'options' => [
                        'placeholder' => 'Выберите семинар ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '350px'
                    ],
                ]),
            ],
            'date_add:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
            ],
        ],
    ]); ?>

</div>
