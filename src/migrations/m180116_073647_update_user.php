<?php

use yii\db\Migration;

/**
 * Class m180116_073647_update_user
 */
class m180116_073647_update_user extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->renameColumn('{{%user}}', 'username', 'name');
        $this->addColumn('{{%user}}', 'group', $this->smallInteger());
        $this->insert('{{%user}}', [
            'name' => 'Admin', 
            'auth_key' => 'W7waylK0-91AksYe439PQ7aVmDoSaBsP', 
            'password_hash' => '$2y$13$b5c5unr8AqoNaZZGzhk8veadx1/SavHCfwPv1IDVdslshmokDSpOy',
            'email' => 'sergmoro1@ya.ru',
            'group' => 1,
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

    }

    public function down()
    {
        $this->renameColumn('{{%user}}', 'name', 'username');
        $this->dropColumn('{{%user}}', 'group');
    }
}
