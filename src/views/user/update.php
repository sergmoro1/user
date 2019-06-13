<?php

/* @var $this yii\web\View */
/* @var $model common\models\User */

use yii\helpers\Html;
use sergmoro1\user\Module;

$this->title = Module::t('core', 'Update');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
