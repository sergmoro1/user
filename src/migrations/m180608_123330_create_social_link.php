<?php

use yii\db\Migration;

/**
 * Class m180608_123330_create_social_link
 */
class m180608_123330_create_social_link extends Migration
{
    public $table = '{{%social_link}}';

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string(255)->notNull(),
            'source_id' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->addForeignKey ('FK_social_link_user', $this->table, 'user_id', '{{%user}}', 'id', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
