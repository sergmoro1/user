<?php
namespace sergmoro1\user\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;

use sergmoro1\user\Module;
use sergmoro1\user\models\SocialLink;

/**
 * BaseUser model class.
 */
class BaseUser extends ActiveRecord implements IdentityInterface
{
    /**
     * The followings are the available columns in table 'user':
     * @var integer $id
     * @var string  $username
     * @var string  $auth_key
     * @var string  $password_hash
     * @var string  $password_reset_token
     * @var string  $email
     * @var integer $group
     * @var integer $status
     * @var integer $created_at
     * @var integer $updated_at
     */

    const STATUS_ACTIVE     = 1;
    const STATUS_ARCHIVED   = 2;

    const GROUP_ADMIN       = 1;
    const GROUP_AUTHOR      = 2;
    const GROUP_COMMENTATOR = 3;
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['status', 'group'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ARCHIVED],
            ['status', 'in', 'range' => self::getStatuses()],
            ['group', 'default', 'value' => self::GROUP_COMMENTATOR],
            ['group', 'in', 'range' => self::getGroups()],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Module::t('core', 'This username has already been taken.')],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Module::t('core', 'This email address has already been taken.')],
            [['auth_key'], 'string', 'max' => 32],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'username'   => Module::t('core', 'Name'),
            'email'      => Module::t('core', 'Email'),
            'group'      => Module::t('core', 'Group'),
            'status'     => Module::t('core', 'Status'),
            'created_at' => Module::t('core', 'Created'),
            'updated_at' => Module::t('core', 'Modified'),
        ];
    }

    /**
     * Get statuses.
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_ARCHIVED, 
        ];
    }

    /**
     * Get groups.
     * @return array
     */
    public static function getGroups() {
        return [
            self::GROUP_ADMIN, 
            self::GROUP_AUTHOR, 
            self::GROUP_COMMENTATOR,
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username.
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     * @param integer $status password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token, $status = self::STATUS_ARCHIVED)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => $status,
        ]);
    }

    /**
     * Finds out if password reset token is valid.
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Get user SocialLink.
     * 
     * @return mixed
     */
    public function getSocialLink()
    {
        return SocialLink::findOne(['user_id' => $this->id]);
    }

    /**
     * Get user avatar from thumb if there is a registered user or social link or icon.
     * Icon may be defined in params.
     * 
     * @param string image $class
     * @param string $icon tag
     * @return string avatar
     */
    public function getAvatar($class = '', $icon = false)
    {
        if($icon === false)
            $icon = Yii::$app->params['icons']['user'];
        if($image = $this->getImage('thumb')) {
            return Html::img($image, ['class' => $class]);
        } else {
            return ($link = $this->getSocialLink())
                ? Html::img($link->avatar, ['class' => $class]) 
                : $icon;
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Get user role name
     */
    public function getRoleName($role)
    {
        return Lookup::item('UserRole', $role);
    }
}
