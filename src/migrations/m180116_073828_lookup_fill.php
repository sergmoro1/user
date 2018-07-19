<?php

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 */
class m180116_073828_lookup_fill extends Migration
{
	const TABLE = '{{%lookup}}';
	const PROPERTY = '{{%property}}';
	const PID = 1; // property ID
    public function up()
    {
		$i = self::PID;
        $this->insert(self::PROPERTY, ['id' => $i, 'name' => 'UserRole']);
        $this->insert(self::TABLE, ['name' => 'Администратор', 'code' => 1, 'property_id' => $i, 'position' => 1]);
        $this->insert(self::TABLE, ['name' => 'Автор', 'code' => 2, 'property_id' => $i, 'position' => 2]);

        $i++;
        $this->insert(self::PROPERTY, ['id' =>  $i, 'name' => 'UserStatus']);
        $this->insert(self::TABLE, ['name' => 'Активен', 'code' => 1, 'property_id' => $i, 'position' => 1]);
        $this->insert(self::TABLE, ['name' => 'Архив', 'code' => 2, 'property_id' => $i, 'position' => 2]);
    }

    public function down()
    {
		$i = self::PID;
        $this->delete(self::TABLE, 'property_id=' . $i);
        $this->delete(self::TABLE, 'property_id=' . ($i + 1));
    }
}
