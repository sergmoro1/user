<?php
namespace sergmoro1\user\models;

use Yii;
use yii\helpers\Url;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use sergmoro1\user\Module;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_ARCHIVED = 2;

    const GROUP_ADMIN = 1;
    const GROUP_AUTHOR = 2;
    
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
            [['status', 'group'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ARCHIVED],
            ['status', 'in', 'range' => [self::STATUS_ARCHIVED, self::STATUS_ACTIVE]],
            ['group', 'default', 'value' => self::GROUP_AUTHOR],
            ['group', 'in', 'range' => [self::GROUP_ADMIN, self::GROUP_AUTHOR]],
			[['name', 'email', 'password_hash', 'password_reset_token'], 'string', 'max'=>255],
			[['auth_key'], 'string', 'max'=>32],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'name' => Module::t('core', 'Name'),
			'password' => Module::t('core', 'Password'),
			'password_repeat' => Module::t('core', 'Repeat the passport'),
			'email' => Module::t('core', 'Email'),
			'group' => Module::t('core', 'Group'),
			'status' => Module::t('core', 'Status'),
			'verifyCode' => Module::t('core', 'Spam protection code'),
			'created_at' => Module::t('core', 'Created'),
			'updated_at' => Module::t('core', 'Modified'),
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
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($name)
    {
        return static::findOne(['name' => $name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
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
     * Finds out if password reset token is valid
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
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
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
        return Lookup::item('Role', $role);
    }

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			$this->updated_at = time();
			if($this->isNewRecord)
				$this->created_at = $this->updated_at;
			return true;
		}
		else
			return false;
	}
}
