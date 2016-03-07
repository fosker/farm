<?php

namespace backend\components;

use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use kartik\widgets\Select2;


class CheckWidget extends InputWidget
{
    public $parent = [];
    public $child = [];

    public $parent_title;
    public $child_title;

    public $relation;

    public $update = [];

    private $values = [];

    public $height;

    public function init()
    {
        parent::init();
        if (!isset($this->height)) {
            $this->height = '200px';
        }
        if (isset($this->update)) {
            foreach($this->update as $items) {
                foreach($items as $item) {
                    $this->values[] = $item;
                }
            }
        }

    }
    public function run()
    {
        echo '<div>';
        $array = ArrayHelper::map($this->child,'id','name');
        echo Select2::widget([
            'name' => 'search_'.$this->child_title,
            'data' => $array,
            'options' => [
                'placeholder' => 'Поиск ...',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        echo "<ul class = 'list-group'>";
        echo  Html::checkbox('all', false, [
        'label' => 'Все',
        ]);


        echo '<div>';
        foreach($this->parent as $parent) {
            $checked = in_array($parent['id'], $this->values);
            echo "<li class='list-group-item'><ul class = 'list-group'>" . Html::checkbox($this->parent_title.'[]', $checked, [
                    'value' => $parent['id'],
                    'label' => $parent['name'],
                ]);
            echo "<div style='height: $this->height; overflow: auto'>";

            foreach($this->child as $child) {
                if($child[$this->relation] == $parent['id']) {
                    $checked = in_array($child['id'], $this->values);
                    echo "<li class='list-group-item'>". Html::checkbox($this->child_title.'[]',
                            $checked
                            , [
                            'value' => $child['id'],
                            'label' => $child['name'],
                        ]) . '</li>';
                }
            };
            echo '</div></ul></li>';
        }
        echo '</div>';

        echo '</ul>';
        echo '</div>';

    }

}