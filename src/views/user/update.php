<?php

use yii\helpers\Html;
use sergmoro1\user\Module;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Module::t('core', 'Update');
?>

<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
