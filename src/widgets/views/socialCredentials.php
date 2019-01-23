<?php
use yii\helpers\Url;
use sergmoro1\user\Module;
?>

<ul class="list-inline social-credencials"><?= $call ?>
<?php foreach($credentials as $id => $client): ?>
    <span class="fa-stack fa-lg">
        <li class="fa fa-circle fa-stack-1x">
            <a href="<?= Url::to(['site/auth', 'authclient' => $id]) ?>" title="<?= Module::t('core', 'Login with') . ' ' . ucfirst($client->id) ?>">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fab fa-<?= $icons[$id] ?> fa-stack-1x fa-inverse"></i>
            </a>
        </li>
    </span>
<?php endforeach; ?>
</ul>
