<?php

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 */
class m180515_085230_lookup_fill extends Migration
{
	const TABLE = '{{%lookup}}';
	const PID = 1; // property ID

    public function up()
    {
		$i = self::PID;
        $this->insert(self::TABLE, ['name' => 'Комментатор', 'code' => 3, 'property_id' => $i, 'position' => 3]);
    }

    public function down()
    {
		$i = self::PID;
        $this->delete(self::TABLE, 'property_id=:property_id AND code=3', [':property_id' => $i]);
    }
}
