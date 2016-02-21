<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Ответы на анкеты';
?>
<div class="reply-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'stock_id',
            'user_id',
            'photo',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
