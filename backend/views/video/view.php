<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\YoutubeWidget;

$this->title = $model->title;
?>
<div class="seminar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['index'],['class'=>'btn btn-info']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить видео и все комментарии?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'link',
                'format' => 'raw',
                'value' =>  Html::tag('div', YoutubeWidget::widget([
                    "code"=> substr($model->link,-11)
                ]), ['class' => 'video-container'])
            ],
            'description:html',
            'tags'
        ],
    ]) ?>

</div>
