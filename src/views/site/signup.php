<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \models\SignupForm */

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

            <?= $form->field($model, 'username')
                ->textInput(['placeholder' => true])
                ->label()
            ?>

            <?= $form->field($model, 'email')
                ->textInput(['placeholder' => true])
                ->label()
            ?>

            <?= $form->field($model, 'password')
                ->passwordInput(['placeholder' => true])
                ->label()
            ?>

            <p>
                <?= Html::submitButton(Module::t('core', 'To signup'), [
                    'class' => 'btn btn-default', 
                    'name' => 'signup-button',
                ]) ?>
            </p>

        <?php ActiveForm::end(); ?>

    </div> <!-- / .col ... -->
</div> <!-- / .row -->

