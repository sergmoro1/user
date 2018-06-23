<?php
namespace sergmoro1\user\widgets;

use yii\base\Widget;

class SocialCredentials extends Widget
{
    public $view = 'socialCredentials';
    public $credentials = [];
    public $icons = [];
    
    public function init() {
        parent::init();
        if(!$this->credentials)
            $this->credentials = \Yii::$app->get('authClientCollection')->clients;
        if(!$this->icons)
            $this->icons = \Yii::$app->params['icons'];
    }
    
    public function run()
    {
        echo $this->render($this->view, [
            'credentials' => $this->credentials,
            'icons' => $this->icons,
        ]);
    }
}
?>
