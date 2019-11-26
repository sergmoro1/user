<?php
namespace sergmoro1\user\models;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * Model to keep social link.
 * @var integer $user_id
 * @var string  $source
 * @var string  $source_id
 * @var string  $avatar
 */
class SocialLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            ['user_id', 'integer'],
            [['source', 'source_id', 'avatar'], 'string', 'max'=>255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUser()
    {
        return User::findOne($this->user_id);
    }
}
