Yii2 module for user registration and management
================================================

Advantages
----------

* registration, email confirmation, reset password, authentification;
* social networks OAuth2 authentification (avatar available);
* backend users management.

Installation
------------

The preferred way to install this extension is through composer.

Either run

`composer require --prefer-dist sergmoro1/yii2-user`

or add

`"sergmoro1/yii2-user": "^1.1"`

to the require section of your composer.json.

Run migration

`php yii migrate --migrationPath=@vendor/sergmoro1/yii2-user/src/migrations`

Configuration
-------------

Add to the configuration file used modules definitions, OAuth2 and mailler components.

```php
return [
  ...
  'modules' => [
    'lookup' => ['class' => 'sergmoro1\lookup\Module'],
    'user'   => ['class' => 'sergmoro1\user\Module'],
  ],
  'components' => [
    'authClientCollection' => [
      'class' => 'yii\authclient\Collection',
      'clients' => [
        'yandex' => [
          'class' => 'yii\authclient\clients\Yandex',
          'clientId' => 'YandexClientId',
          'clientSecret' => 'YandexClientSecret',
        ],
        ...
      ],
      'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => false,
        'viewPath' => '@vendor/sergmoro1/yii2-user/src/mail',
        /* Example of definition (Yandex)
        'transport' => [
          'class' => 'Swift_SmtpTransport',
          'host' => 'smtp.yandex.ru',
          'username' => 'admin@your-site.ru',
          'password' => 'your-password',
          'port' => '465',
          'encryption' => 'ssl',
        ],
        */
      ],
    ],
  ],
```

Usage
-----

Add action for OAuth2 authentification with social network accounts to controller.

```php
namespace frontend\controllers;

use Yii;
use sergmoro1\user\traits\AuthTrait;

class SiteController extends Controller
{
    use AuthTrait;

    /**
     * Handler for EVENT_AFTER_LOGGED_IN. May be defined if needed.
     */
    public function init()
    {
        parent::init();
        $this->on(\sergmoro1\user\Module::EVENT_AFTER_LOGGED_IN, Yii::$app->session->setFlash('success', 
            Yii::t('app', 'You are logged in as a commentator. You can leave a comment now.')
        ));
    }
    
    /**
     * inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }
```


