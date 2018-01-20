<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use sergmoro1\user\Module;

$this->title = Module::t('core', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-lg-6">

<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->field($model, 'email')
		->textInput(['placeholder' => $model->getAttributeLabel('email')])
		->label($model->getAttributeLabel('email'))
	?>

	<br>
	<?= Html::submitButton(Module::t('core', 'Submit'), [
		'class' => 'btn btn-default', 
		'name' => 'submit-button',
	]); ?>
	<br><br>

<?php ActiveForm::end(); ?>

</div>
<div class="col-lg-6">
	<img src='<?= $frontend ?>/files/site/page/request-token-bg.jpg' height='100%' width='100%'>
</div>
</div>
