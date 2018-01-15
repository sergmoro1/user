<h1>Yii2 module for user. Registration, login, logout, management.</h1>

<h2>Advantages</h2>

Used with sergmoro1/yii2-blog-tools module but can be used separately.

<ul>
  <li>registration;</li>
  <li>email confirmation;</li>
  <li>authentification;</li>
  <li>users management.</li>
</ul>

<h2>Installation</h2>

In app directory:

<pre>
$ composer require sergmoro1/yii2-blog-user "dev-master"
</pre>

Run migration:
<pre>
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-user/migrations
</pre>

<h2>Usage</h2>

Set up in <code>backend/config/main.php</code> default layout and tree modules. Two of them was setted up automatically.

<pre>
return [
    ...
    // if sergmoro1/blog-tools was set up
    'layoutPath' => '@vendor/sergmoro1/yii2-blog-tools/src/views/layouts',
    ...
    'modules' => [
		'uploader' => ['class' => 'sergmoro1\uploader\Module'],
		'lookup' => ['class' => 'sergmoro1\lookup\Module'],
		'user' => ['class' => 'sergmoro1\user\Module'],
		// recomemnded
		'blog' => ['class' => 'sergmoro1\blog\Module'],
    ],
</pre>
