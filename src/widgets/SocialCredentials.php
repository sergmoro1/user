<?php
namespace sergmoro1\user\widgets;

use Yii;
use yii\base\Widget;
use sergmoro1\user\Module;

/**
 * Widget for guest login to the site with social credentials using OAuth2.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
 */
class SocialCredentials extends Widget
{
    public $call = '';
    public $credentials = [];
    public $icons = [];
    
    public function init() {
        parent::init();
        if($this->call === '')
            $this->call = Module::t('core', 'You can log in using the social network');
        if(!$this->credentials)
            $this->credentials = Yii::$app->get('authClientCollection')->clients;
        if(!$this->icons)
            $this->icons = Yii::$app->params['icons'];
    }
    
    public function run()
    {
        echo $this->render('socialCredentials', [
            'call' => $this->call,
            'credentials' => $this->credentials,
            'icons' => $this->icons,
        ]);
    }
}
?>
