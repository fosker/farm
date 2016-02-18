<?php

Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('rest', dirname(dirname(__DIR__)) . '/rest');

Yii::setAlias('uploads', dirname(dirname(__DIR__)) . '/uploads');

Yii::setAlias('uploads_view', 'http://'.$_SERVER['HTTP_HOST'].'/uploads');
//Yii::setAlias('uploads_view', 'http://farm.data');