<?php

namespace backend\components;

use yii\widgets\InputWidget;
use yii\helpers\Html;


class CheckWidget extends InputWidget
{
    public $parent = [];
    public $child = [];

    public $parent_title;
    public $child_title;

    //'region_id', 'firm_id'
    public $relation;

    public $update = [];

    private $values = [];

    public function init()
    {
        parent::init();
        if (isset($this->update)) {
            foreach($this->update as $cities) {
                foreach($cities as $city) {
                    $this->values[] = $city;
                }
            }
        }

    }

    public function run()
    {
        echo '<div>';
        echo "<ul class = 'list-group'>";
        echo  Html::checkbox('all', false, [
        'label' => 'Все',
        ]);

        foreach($this->parent as $parent) {
            echo "<li class='list-group-item'><ul class = 'list-group'>" . Html::checkbox($this->parent_title.'[]', false, [
                    'value' => $parent['id'],
                    'label' => $parent['name'],
                    'onchange'=>"js:
                    for (i = 0; i < this.parentElement.nextElementSibling.children.length; i++) {
                        if (this.checked) {
                            this.parentElement.nextElementSibling.children[i].children[0].children[0].checked = true;
                        } else
                        this.parentElement.nextElementSibling.children[i].children[0].children[0].checked = false;
                    }"
                ]);
            echo "<div style='height: 200px; overflow: auto'>";

            foreach($this->child as $child) {
                if($child[$this->relation] == $parent['id']) {
                    $checked = in_array($child['id'], $this->values);
                    echo "<li class='list-group-item'>". Html::checkbox($this->child_title.'[]',
                            $checked
                            , [
                            'value' => $child['id'],
                            'label' => $child['name'],
                            'onchange'=>"js:
                            if (this.checked) {
                                this.parentNode.parentNode.parentNode.previousElementSibling.children[0].checked = true
                            }"
                        ]) . '</li>';
                }
            };
            echo '</div></ul></li>';
        }

        echo '</ul>';
        echo '</div>';

    }

}