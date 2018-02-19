<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use sergmoro1\user\Module;

$this->title = Module::t('core', 'Login');
$this->params['breadcrumbs'][] = Module::t('core', 'Access to content management');
?>

<div class="row">
<div class="col-lg-6">

<p>
	<?php echo Module::t('core', 'This page is for registered users. If it\'s not about you, you can go through a simple ') . 
		Html::a(Module::t('core', 'registration'), ['site/signup']); ?>.
</p>

<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->field($model, 'name')
		->textInput(['placeholder' => $model->getAttributeLabel('name')])
		->label($model->getAttributeLabel('name'))
	?>

	<?= $form->field($model, 'password')
		->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
		->label($model->getAttributeLabel('password'))
	?>
	
	<?= $form->field($model, 'rememberMe')->checkBox(); ?>
	
	<p>
		<?php echo Module::t('core', 'If you forgot your password you can') . ' ' .
			Html::a(Module::t('core', 'reset it'), ['site/request-password-reset']); ?>.
	</p>

	<?php echo Html::submitButton(Module::t('core', 'Login'), [
		'class'=>'btn btn-default',
		'name' => 'login-button',
	]); ?>	
	<br><br>

<?php ActiveForm::end(); ?>

</div> <!-- / .col ... -->
</div> <!-- / .row -->
