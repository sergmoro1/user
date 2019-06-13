<?php

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sergmoro1\uploader\widgets\Byone;
use sergmoro1\lookup\models\Lookup;
use sergmoro1\user\Module;

use common\models\User;
?>

<?php $form = ActiveForm::begin(); ?>
<div class='row'>
    <div class="col-lg-8">
        <?= Byone::widget([
            'model' => $model,
            'appendixView' => '/user/appendix',
            'cropAllowed' => true,
            'draggable' => true,
        ]) ?>

        <?= $form->field($model, 'username')
            ->textInput(['maxlength' => true])
        ?>

        <?= $form->field($model, 'email')
            ->textInput(['maxlength' => true]) 
        ?>

        <div class="form-group">
            <?= Html::submitButton(Module::t('core', 'Save'), [
                'class' => 'btn btn-success',
            ]) ?>
        </div>
    </div>

    <div class="col-lg-4">

        <?php if(\Yii::$app->user->can('gear')): ?>
            <?= $form->field($model, 'status')->dropdownList(
                Lookup::items('UserStatus')
            ); ?>

            <?= $form->field($model, 'group')->dropdownList(
                Lookup::items('UserRole')
            ); ?>

            <div class="form-group">
                <?= Html::submitButton(Module::t('core', 'Save'), [
                    'class' => 'btn btn-success',
                ]) ?>
            </div>
        <?php endif; ?>
        
        <?= $this->render('help') ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
