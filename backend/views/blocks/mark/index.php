<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

$this->title = 'Оценки';
?>
<div class="mark-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Автор',
                'attribute'=>'user.name',
                'value'=>function($model) {
                    return Html::a($model->user->name, ['/user/view', 'id'=>$model->user_id]);
                },
                'format'=>'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $users,
                    'attribute'=>'user.name',
                    'options' => [
                        'placeholder' => 'Выберите пользователя ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '350px'
                    ],
                ]),
            ],
            [
                'attribute'=>'block_id',
                'label'=>'Страница',
                'value'=>function($model) {
                    return Html::a($model->block->title, ['/block/view', 'id' => $model->block_id]);
                },
                'format'=>'html',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'data' => $blocks,
                    'attribute'=>'block_id',
                    'options' => [
                        'placeholder' => 'Выберите страницу ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '350px'
                    ],
                ]),
            ],
            [
                'attribute'=>'mark',
                'filter'=>[1=>1,2=>2,3=>3,4=>4,5=>5],
            ],
            'date_add:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
            ],
        ],
    ]); ?>

</div>
