<?php

use common\models\User;
use kartik\widgets\Growl;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

$this->title = 'Пользователи, ожидающие подтверждение обновления';
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'user_id',
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{accept} {view} {delete}',
                'buttons'=>[
                    'accept' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-ok"></i>', ['user/update', 'id' => $model->user_id, 'update_id'=>$model->user_id]);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['view', 'user_id'=>$model->user_id]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'user_id'=>$model->user_id], [
                            'title'=>'Удалить',
                            'data-confirm' => 'Вы уверены, что хотите удалить запрос на обновление пользователя?',
                            'data-pjax'=>0,
                            'data-method'=>'post',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
