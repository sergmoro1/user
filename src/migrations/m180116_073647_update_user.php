<?php

use yii\db\Migration;

/**
 * Class m180116_073647_update_user
 */
class m180116_073647_update_user extends Migration
{
    private const TABLE_USER = '{{%user}}';

    // Use up()/down() to run migration code without a transaction.
    public function safeUp()
    {
        $this->addColumn(static::TABLE_USER, 'group', $this->smallInteger());
        $this->insert('{{%user}}', [
            'username'      => 'Admin', 
            'auth_key'      => 'W7waylK0-91AksYe439PQ7aVmDoSaBsP', 
            'password_hash' => '$2y$13$b5c5unr8AqoNaZZGzhk8veadx1/SavHCfwPv1IDVdslshmokDSpOy',
            'email'         => 'admin@example.ru',
            'group'         => 1,
            'status'        => 1,
            'created_at'    => time(),
            'updated_at'    => time(),
        ]);

    }

    public function safeDown()
    {
        $this->dropColumn(static::TABLE_USER, 'group');
    }
}
