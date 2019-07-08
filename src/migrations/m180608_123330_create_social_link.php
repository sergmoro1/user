<?php

use yii\db\Migration;

/**
 * Class m180608_123330_create_social_link
 */
class m180608_123330_create_social_link extends Migration
{
    private const TABLE_SOCIAL_LINK  = '{{%social_link}}';
    private const TABLE_USER         = '{{%user}}';

    // Use up()/down() to run migration code without a transaction.
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_SOCIAL_LINK, [
            'id'        => $this->primaryKey(),
            'user_id'   => $this->integer()->notNull(),
            'avatar'    => $this->string(255),
            'source'    => $this->string(255)->notNull(),
            'source_id' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->addForeignKey ('fk-social_link-user', static::TABLE_SOCIAL_LINK, 'user_id', static::TABLE_USER, 'id', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_SOCIAL_LINK);
    }
}
