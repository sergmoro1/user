<?php
use yii\helpers\Url;
use sergmoro1\user\Module;
?>

<p><?= Module::t('core', 'You can log in using the social network.') ?></p>

<ul class="list-inline">
<?php foreach($credentials as $id => $client): ?>
	<span class="fa-stack fa-lg">
		<li class="fa fa-circle fa-stack-1x">
			<a href="<?= Url::to(['site/auth', 'authclient' => $id]) ?>">
			    <i class="fa fa-circle fa-stack-2x"></i>
			    <i class="fa fa-<?= $icons[$id] ?> fa-stack-1x fa-inverse"></i>
			</a>
		</li>
	</span>
<?php endforeach; ?>
</ul>
