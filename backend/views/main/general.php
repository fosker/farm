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

    <div class="settings-form">

	    <?php $form = ActiveForm::begin(); ?>

	  	<?php  foreach ($settings as $index => $setting) {
    		echo $form->field($setting, "[$index]value")->label($setting->name);
		} ?>


	    <div class="form-group">
	        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>


</div>