<?php
namespace sergmoro1\user\components;

use yii\validators\Validator;
use sergmoro1\user\Module;

/**
 * Check the existence of the user and if it exists, whether the password is correct. 
 * Error if not exists together.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
 */
class UserExistsPasswordValid extends Validator
{
    public $urlPasswordValid;
    
    public function init()
    {
        parent::init();
        $this->message = Module::t('core', 'Incorrect username or password.');
    }

    public function validateAttribute($model, $attribute)
    {
        $user = $model->getUser();
        if (!$model->hasErrors()) {
            if (!$user || !$user->validatePassword($model->password)) {
                $model->addError($attribute, $this->message);
            }
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $url = json_encode($this->urlPasswordValid);
        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return <<<JS
var entity = {exists: false};
$.ajax({
    type: 'POST',
    url: $url, 
    data: {username: $('#loginform-username').val(), password: $('#loginform-password').val()},
    dataType: "json",
    async:false
}).done(function(response) {
    entity.exists = response;
});
// An error pushed if entity not exists.
if(!entity.exists)
    messages.push($message);
JS;
    }
}
