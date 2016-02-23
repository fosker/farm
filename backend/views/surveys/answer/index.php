<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use kartik\widgets\Select2;

$this->title = 'Ответы на анкеты';
?>
<div class="answer-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'ID Анкеты',
                'attribute'=>'view.survey.id',
                'value'=>'view.survey.id',
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            [
                'attribute'=>'view.survey.title',
                'value'=>function($model) {
                    return Html::a($model->view->survey->title, ['/survey/view', 'id'=>$model->view->survey->id]);
                },
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $surveys,
                    'attribute'=>'view.survey.title',
                    'options' => [
                        'placeholder' => 'Выберите анкету ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '300px'
                    ],
                ]),
                'format'=>'html',
            ],
            [
                'label' => 'Логин пользователя',
                'attribute'=>'view.user.login',
                'value'=>function($model) {
                    return Html::a($model->view->user->login, ['/user/view', 'id'=>$model->view->user->id]);
                },
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $logins,
                    'attribute'=>'view.user.id',
                    'options' => [
                        'placeholder' => 'Выберите пользователя ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '300px'
                    ],
                ]),
                'format'=>'html',
            ],
            [
                'attribute' => 'view.added',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'view.added',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                    ]
                ]),
                'format' => ['date', 'php:Y-m-d'],
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
                'buttons'=> [
                    'view'=>function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i> ', ['view', 'user_id'=>$model->view->user->id, 'survey_id'=>$model->view->survey->id], [
                            'title'=>'Просмотреть',
                        ]);
                    },
                    'delete'=>function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i> ', ['delete', 'user_id'=>$model->view->user->id, 'survey_id'=>$model->view->survey->id], [
                            'title'=>'Удалить',
                            'data-pjax'=>0,
                            'data-method'=>'post',
                            'data-confirm'=>'Удалить ответ?',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
