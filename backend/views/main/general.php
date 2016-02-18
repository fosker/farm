<?php

use kartik\widgets\Growl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $settings array */

$this->title = 'Site settings & statistics';

if(Yii::$app->session->hasFlash('GeneralSettingsMessage')) :
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Успешно',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('GeneralSettingsMessage'),
        'showSeparator' => true,
        'delay' => 0,
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ]);
endif;

?>
<div class="main-settings">
	
    <h1>Настройки</h1>

    <h3 class="bg-warning">Страница на стадии разработки</h3>

    <p>Это страница общих настроек приложения. Здесь будет раполагаться управление параметрами, такими, как, например, обратный адрес email-рассылок,  котнактные данные и пр.</p>

    <div class="settings-form">

	    <?php $form = ActiveForm::begin(); ?>

	  	<?  foreach ($settings as $index => $setting) {
    		echo $form->field($setting, "[$index]value")->label($setting->name);
		} ?>


	    <div class="form-group">
	        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>


</div>