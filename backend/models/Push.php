<?php

namespace backend\models;
use yii\base\Model;

class Push extends Model
{
    public $regions = [];
    public $cities = [];
    public $pharmacies = [];
    public $educations = [];
    public $users = [];

    public $message;

    public function attributeLabels() {
        return [
            'regions' => 'Регионы',
            'cities' => 'Города',
            'pharmacies' => 'Аптеки',
            'education' => 'Образования',
            'message' => 'Сообщение'
        ];
    }

    public function rules()
    {
        return [
            [['message'], 'required'],
        ];
    }

}