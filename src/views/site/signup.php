<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \models\SignupForm */

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use sergmoro1\user\Module;

$this->title = Module::t('core', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-lg-6">

<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->field($model, 'name')
		->textInput(['placeholder' => $model->getAttributeLabel('name')])
		->label($model->getAttributeLabel('name'))
	?>

	<?= $form->field($model, 'email')
		->textInput(['placeholder' => $model->getAttributeLabel('email')])
		->label($model->getAttributeLabel('email'))
	?>

	<?= $form->field($model, 'password')
		->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
		->label($model->getAttributeLabel('password'))
	?>

	<br>
	<?= Html::submitButton(Module::t('core', 'To signup'), [
		'class' => 'btn btn-default', 
		'name' => 'signup-button',
	]) ?>
	<br><br>

<?php ActiveForm::end(); ?>

</div> <!-- / .col ... -->
<div class="col-lg-6">
	<img src='<?= $frontend ?>/files/site/page/signup-bg.jpg' height='100%' width='100%'>
</div>
</div> <!-- / .row -->

