<?php

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 */
class m180515_085230_lookup_fill extends Migration
{
    const LOOKUP = '{{%lookup}}';
    const USER_ROLE = 1;

    public function up()
    {
        $this->insert(self::LOOKUP, ['name' => 'Комментатор', 'code' => 3, 'property_id' => self::USER_ROLE, 'position' => 3]);
    }

    public function down()
    {
        $this->delete(self::LOOKUP, 'property_id=:property_id AND code=3', [':property_id' => self::USER_ROLE]);
    }
}
