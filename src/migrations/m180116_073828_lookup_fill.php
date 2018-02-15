<?php

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 */
class m180116_073828_lookup_fill extends Migration
{
    public function up()
    {
		$this->insert('{{%lookup}}', ['name' => 'Администратор', 'code' => 1, 'type' => 'UserRole', 'position' => 1]);
		$this->insert('{{%lookup}}', ['name' => 'Автор', 'code' => 2, 'type' => 'UserRole', 'position' => 2]);

		$this->insert('{{%lookup}}', ['name' => 'Активен', 'code' => 1, 'type' => 'UserStatus', 'position' => 1]);
		$this->insert('{{%lookup}}', ['name' => 'Архив', 'code' => 2, 'type' => 'UserStatus', 'position' => 2]);
    }

    public function down()
    {
		$this->delete('{{%lookup}}', 'type=:type', ['type' => 'UserRole']);
		$this->delete('{{%lookup}}', 'type=:type', ['type' => 'UserStatus']);
    }
}
