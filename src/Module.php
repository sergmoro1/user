<?php
namespace sergmoro1\user;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @event AfterLoggedIn an event that occurred after the user logs in.
     */
    const EVENT_AFTER_LOGGED_IN = 'afterLoggedIn';
    
    public $controllerNamespace = 'sergmoro1\user\controllers';
    public $sourceLanguage = 'en-US';

    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    /**
     * Register translate messages for module
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['sergmoro1/user/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => $this->sourceLanguage,
            'basePath' => '@vendor/sergmoro1/yii2-user/src/messages',
            'fileMap' => [
                'sergmoro1/user/core' => 'core.php',
            ],
        ];
    }

    /**
     * Translate shortcut
     *
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('sergmoro1/user/' . $category, $message, $params, $language);
    }
}
