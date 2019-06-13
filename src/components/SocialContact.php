<?php
/**
 * Class retrieve social contacts depending on social nets.
 * 
 * @author Seregey Morozov <sergey@vorst.ru>
 */

namespace sergmoro1\user\components;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidValueException;
use sergmoro1\user\models\SocialLink;
use common\models\User;
use sergmoro1\user\Module;

class SocialContact extends BaseObject
{
    public $id;
    public $username;
    public $email;
    public $avatar;
    
    private $_link;
    
    /**
     * Retrieve id, name, email and, may be more.
     * 
     * @param string social client
     */
    public function __construct($client, $config = [])
    {
        $client_id = $client->getId();
        // OAuth2 response
        $attributes = $client->getUserAttributes();
        $this->id = (string)$attributes['id'];
        
        // convert from individual to a single view of attributes
        $class_name = 'sergmoro1\\user\\components\\convertor\\' . ucfirst($client_id);
        $convertor = new $class_name();
        $convertor->set($this, $attributes);
        
        // if email not setted then make it
        if (!$this->email)
            $this->email = "{$attributes['id']}@{$client_id}.net";
        
        parent::__construct($config);
    }
    
    public function registration($client_id)
    {
        if (User::find()->where(['email' => $this->email])->exists()) {
            Yii::$app->getSession()->setFlash('error', [
                Module::t('core', 'User with {email} have been exist, but not linked to {client}. ' . 
                    'Try to login with other social network or with name and password.', [
                    'email' => $this->email,
                    'client' => $client_id,
                ]),
            ]);
        } else {
            if(User::find()->where(['username' => $this->username])->exists()) {
                Yii::$app->getSession()->setFlash('error', [
                    Module::t('core', 'A user named {name} already exists. Try logging in if you have registered before.', [
                        'name' => $this->username,
                    ]),
                ]);
            } else {
                $password = Yii::$app->security->generateRandomString(6);
                $user = new User([
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => $password,
                    'status' => User::STATUS_ACTIVE,
                ]);
                $user->generateAuthKey();
                $user->generatePasswordResetToken();
                $transaction = $user->getDb()->beginTransaction();
                
                if ($user->save()) {
                    if ($this->makeLink($client_id, $user->id)) {
                        $transaction->commit();
                        Yii::$app->user->login($user);
                    } else {
                        throw new InvalidValueException($this->showErrors($this->_link)); 
                    }
                } else {
                    throw new InvalidValueException($this->showErrors($user)); 
                }
            }
        }
    }
    
    public function makeLink($client_id, $user_id)
    {
        $this->_link = new SocialLink([
            'user_id' => $user_id,
            'source' => $client_id,
            'source_id' => $this->id,
            'avatar' => $this->avatar,
        ]);
        return $this->_link->save();
    }
    
    private function showErrors($model)
    {
        $out = 'Can\'t save ' . $model->tableName() . "\n";
        foreach($model->getErrors() as $field => $messages) {
            $out .= "«{$field}»\n";
            foreach($messages as $message) {
                $out .= "{$message}\n";
            }
            $out .= "\n";
        }
        return $out;
    }
}
