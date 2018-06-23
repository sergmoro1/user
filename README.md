<h1>Yii2 module for user. Registration, login, logout, management.</h1>

<h2>Advantages</h2>

Used with sergmoro1/yii2-blog-tools module but can be used separately.

<ul>
  <li>registration;</li>
  <li>email confirmation;</li>
  <li>authentification;</li>
  <li>social networks OAuth authentification, avatar available (Yandex, Vkontakte, Google, GitHub);</li>
  <li>users management.</li>
</ul>

<h2>Installation</h2>

In app directory:

<pre>
$ composer require --prefer-dist sergmoro1/yii2-user "dev-master"
</pre>

Run migration:
<pre>
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-user/src/migrations
</pre>

<h2>Recomendation</h2>

Use this module in addition to <code>sergmoro1/yii2-blog-tools</code> module.

<h2>Usage</h2>

Set up in <code>backend/config/main.php</code> or <code>common/config/main.php</code>.

<pre>
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
</pre>

Add action for OAuth2 authontification with social network accounts to <code>frontend/controllers/SiteController.php</code>.

<pre>
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
                $user = $social_link->user;
                Yii::$app->user->login($user);
            } else { // registration
                $social_contact->registration($client->getId());
            }
        } else { // the user is already registered
            if (!$social_link) { // add external service of authentification
                $social_contact->makeLink($client->getId(), Yii::$app->user->id);
            }
        }
    }
</pre>
