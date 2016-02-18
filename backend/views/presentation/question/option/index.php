<?php

use yii\helpers\Html;

$this->title = 'Варианты ответа';
?>
<div class="option-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить вариант ответа', ['add-option','question_id'=>$_GET['question_id']], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table">
        <tr><th>Вариант ответа</th><th>Действия</th></tr>
        <? foreach($options as $option) : ?>
            <tr>
                <td><?=$option->value?></td>
                <td>

                    <?=Html::a('<span class="glyphicon glyphicon-pencil"></span>',['edit-option', 'id'=>$option->id],['class'=>'btn btn-primary btn-xs']);?>

                    <?=Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        ['delete-option', 'id'=>$option->id],
                        ['class'=>'btn btn-danger btn-xs',
                            'data' => [
                                'confirm' => 'Удалить вариант ответа?',
                                'method' => 'post',
                            ]
                        ]
                    );?>
                </td>
            </tr>
        <? endforeach; ?>
    </table>

</div>
