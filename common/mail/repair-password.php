<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 24.09.2015
 * Time: 18:38

 * @var $route string
*/
use yii\helpers\Html;
use yii\helpers\Url;

?>

<p>Перейдите по <?= Html::a('ссылке',Url::to($route,true)); ?>.</p>

Или скопируйте в строку браузера: <?=Url::to($route,true);?>