<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use sergmoro1\user\Module;

$this->title = Module::t('core', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-lg-6">

<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->field($model, 'password')
		->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
		->label($model->getAttributeLabel('password'))
	?>

	<br>
	<?= Html::submitButton(Module::t('core', 'Save'), [
		'class' => 'btn btn-default', 
		'name' => 'save-button',
	]); ?>
	<br><br>

<?php ActiveForm::end(); ?>

</div>
</div>
