<?php
namespace sergmoro1\user\models;

use Yii;
use yii\helpers\Url;
use yii\base\Model;
use sergmoro1\user\Module;

use common\models\User;

/**
 * Login form
 * @var string  $username
 * @var string  $password
 * @var boolean $rememberMe
 */
class LoginForm extends Model
{
    public $groups = [];
    
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    public $urlPasswordValid;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // Set url for password verification by Ajax request 
        if(!$this->urlPasswordValid)
            $this->urlPasswordValid = Url::to(['user/user/password-valid']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            [['username', 'password'], 'sergmoro1\user\components\UserExistsPasswordValid', 'urlPasswordValid' => $this->urlPasswordValid],
            // registered user should also belongs to group
            ['username', 'allowedUserGroups', 'params' => ['groups' => $this->groups]],
        ];
    }

    /**
     * Validates the User group.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function allowedUserGroups($attribute, $params)
    {
        if(!($user = $this->getUser()))
            // user has not registered yet
            return;
        if ($params['groups'] && !in_array($user->group, $params['groups'])) {
            $this->addError($attribute, 
                Module::t('core', 'You are registered, but you belong to a group that is not allowed to enter this part of the site.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'   => Module::t('core', 'Username'),
            'password'   => Module::t('core', 'Password'),
            'rememberMe' => Module::t('core', 'Remember me'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && ($user = $this->getUser())) {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
