<?php
/**
 * @author sergmoro1@ya.ru
 * @license MIT
 * 
 * Retrieve social contacts depending on social nets.
 * 
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
    public $name;
    public $email;

    /**
     * Retrieve id, name, email and, may be more.
     * 
     * @param string social client
     * @param array attributes (OAuth2 response)
     */
    public function __construct($client, $config = [])
    {
		$client_id = $client->getId();
		$attributes = $client->getUserAttributes();
		$this->id = (string)$attributes['id'];
		
        switch ($client_id) {
		case 'yandex' :
			$this->name = $attributes['first_name'] . ' ' . $attributes['last_name'];
			$this->email = isset($attributes['default_email']) ? $attributes['default_email'] : false;
			break;
		case 'vkontakte' :
			$this->name = $attributes['first_name'] . ' ' . $attributes['last_name'];
			$this->email = isset($attributes['email']) ? $attributes['email'] : false;
			break;
		case 'odnoklassniki' :
			$this->name = $attributes['first_name'] . ' ' . $attributes['last_name'];
			$this->email = isset($attributes['email']) ? $attributes['email'] : false;
			break;
		case 'google' :
			$this->name = $attributes['name']['givenName'] . ' ' . $attributes['name']['familyName'];
			$this->email = isset($attributes['emails'][0]['value']) ? $attributes['emails'][0]['value'] : false;
			break;
		case 'facebook' :
			$this->name = $attributes['name'];
			$this->email = isset($attributes['email']) ? $attributes['email'] : false;
			break;
		case 'twitter' :
			$this->name = $attributes['name'];
			// twitter shows email only the first time
			$this->email = isset($attributes['email']) ? $attributes['email'] : false;
			break;
		}
		// if email not setted then make it
		if (!$this->email)
			$this->email = "{$attributes['id']}@{$client_id}.net";
		
		parent::__construct($config);
    }
    
    public function registration($client_id)
    {
        if (User::find()->where(['email' => $this->email])->exists()) {
            Yii::$app->getSession()->setFlash('error', [
                Module::t('core', 'User with {email} for {client} have been exist, but not linked to each other.', [
                    'email' => $this->email,
                    'client' => $this->id,
                ]),
            ]);
        } else {
            $password = Yii::$app->security->generateRandomString(6);
            $user = new User([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $password,
                'status' => User::STATUS_ACTIVE,
            ]);
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $transaction = $user->getDb()->beginTransaction();
            if ($user->save()) {
                $social_link = new SocialLink([
                    'user_id' => $user->id,
                    'source' => $client_id,
                    'source_id' => $this->id,
                ]);
                if ($social_link->save()) {
                    $transaction->commit();
                    Yii::$app->user->login($user);
                } else {
                    throw new InvalidValueException($this->showErrors($social_link)); 
                }
            } else {
                throw new InvalidValueException($this->showErrors($user)); 
            }
        }
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
