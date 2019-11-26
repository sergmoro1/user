<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */

use yii\helpers\Html;
use sergmoro1\user\Module;

$resetLink = \Yii::$app->urlManager->createAbsoluteUrl(['user/site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?= Module::t('core', 'Hello') ?> <?= Html::encode($user->name) ?>,</p>

    <p><?= Module::t('core', 'Follow the link below to reset your password') ?>:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
