<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;


$this->title = 'Ответы на презентации';
?>
<div class="presentation-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'ID Презентации',
                'attribute'=>'view.presentation.id',
                'value'=>'view.presentation.id',
                'contentOptions'=>['style'=>'width: 150px;'],
            ],
            [
                'attribute'=>'view.presentation.title',
                'value'=>function($model) {
                    return Html::a($model->view->presentation->title, ['/presentation/view', 'id'=>$model->view->presentation->id]);
                },
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $titles,
                    'attribute'=>'view.presentation.title',
                    'options' => [
                        'placeholder' => 'Выберите презентацию ...',
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
                    'attribute'=>'view.user.login',
                    'options' => [
                        'placeholder' => 'Выберите логин пользователя ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '300px'
                    ],
                ]),
                'format'=>'html',
            ],
            'view.added:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
                'buttons'=> [
                    'view'=>function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i> ', ['view', 'user_id'=>$model->view->user->id, 'presentation_id'=>$model->view->presentation->id], [
                            'title'=>'Просмотреть',
                        ]);
                    },
                    'delete'=>function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i> ', ['delete', 'user_id'=>$model->view->user->id, 'presentation_id'=>$model->view->presentation->id], [
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
