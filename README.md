Yii2 module for user registration, login, logout, management
============================================================

Advantages
----------

Used with sergmoro1/yii2-blog-tools module but can be used separately.

* registration;
* email confirmation;
* authentification;
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

Set up in `backend/config/main.php` or `common/config/main.php`.

```php
return [
  ...
  'modules' => [
    'uploader' => ['class' => 'sergmoro1\uploader\Module'],
    'lookup' => ['class' => 'sergmoro1\lookup\Module'],
    'user' => ['class' => 'sergmoro1\user\Module'],
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
        /* Definition of Yandex post office for your domain (example).
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

Add action for OAuth2 authontification with social network accounts to `frontend/controllers/SiteController.php`.

```php
namespace frontend\controllers;

use common\models\User;
use sergmoro1\user\models\SocialLink;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $social_contact = new SocialContact($client);

        $social_link = SocialLink::find()->where([
            'source' => $client->getId(),
            'source_id' => $social_contact->id,
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($social_link) { // authorization
                Yii::$app->user->login($social_link->user);
            } else { // registration
                $social_contact->registration($client->getId());
            }
        } else { // the user is already registered
            if (!$social_link) { // add external service of authentification
                $social_contact->makeLink($client->getId(), Yii::$app->user->id);
            }
        }
    }
```

Recomendation
-------------

Use this module in addition to `sergmoro1/yii2-blog-tools` module.
Especially take a look `common/models/User.php` after `initblog`.

