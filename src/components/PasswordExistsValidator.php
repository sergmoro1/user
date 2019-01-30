<?php
namespace sergmoro1\user\components;

use yii\validators\Validator;
use sergmoro1\user\Module;

/**
 * Check uniqueness by multiple values. 
 * If the values have not changed, we believe that the uniqueness is preserved. 
 * If one or more values have changed, we looking for a combination in the table. 
 * Error if exists.
 */
class PasswordExistsValidator extends Validator
{
    public $urlPasswordExists;
    
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
        $url = json_encode($this->urlPasswordExists);
        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return <<<JS
var entity = {exists: false};
$.ajax({
    url: $url, 
    data: {name: $('#loginform-name').val(), password: $('#loginform-password').val()},
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
