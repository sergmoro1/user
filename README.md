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
  'modules' =&gt; [
    'uploader' =&gt; ['class' =&gt; 'sergmoro1\uploader\Module'],
    'lookup' =&gt; ['class' =&gt; 'sergmoro1\lookup\Module'],
    'user' =&gt; ['class' =&gt; 'sergmoro1\user\Module'],
  ],
  'components' =&gt; [
    'authClientCollection' =&gt; [
      'class' =&gt; 'yii\authclient\Collection',
      'clients' =&gt; [
        'yandex' =&gt; [
          'class' =&gt; 'yii\authclient\clients\Yandex',
          'clientId' =&gt; 'YandexClientId',
          'clientSecret' =&gt; 'YandexClientSecret',
        ],
        ...
      ],
      'mailer' =&gt; [
        'class' =&gt; 'yii\swiftmailer\Mailer',
        'useFileTransport' =&gt; false,
        'viewPath' =&gt; '@vendor/sergmoro1/yii2-user/src/mail',
        /* Definition of Yandex post office for your domain (example).
        'transport' =&gt; [
          'class' =&gt; 'Swift_SmtpTransport',
          'host' =&gt; 'smtp.yandex.ru',
          'username' =&gt; 'admin@your-site.ru',
          'password' =&gt; 'your-password',
          'port' =&gt; '465',
          'encryption' =&gt; 'ssl',
        ],
        */
      ],
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
            'auth' =&gt; [
                'class' =&gt; 'yii\authclient\AuthAction',
                'successCallback' =&gt; [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $social_contact = new SocialContact($client);

        $social_link = SocialLink::find()-&gt;where([
            'source' =&gt; $client-&gt;getId(),
            'source_id' =&gt; $social_contact-&gt;id,
        ])-&gt;one();
        
        if (Yii::$app-&gt;user-&gt;isGuest) {
            if ($social_link) { // authorization
                Yii::$app-&gt;user-&gt;login($social_link-&gt;user);
            } else { // registration
                $social_contact-&gt;registration($client-&gt;getId());
            }
        } else { // the user is already registered
            if (!$social_link) { // add external service of authentification
                $social_contact-&gt;makeLink($client-&gt;getId(), Yii::$app-&gt;user-&gt;id);
            }
        }
    }
</pre>
