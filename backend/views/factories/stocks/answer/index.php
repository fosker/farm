<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\factory\answer\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Replies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reply-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Reply', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
