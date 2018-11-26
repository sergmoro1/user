<?php

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 */
class m180116_073828_lookup_fill extends Migration
{
    const LOOKUP = '{{%lookup}}';
    const PROPERTY = '{{%property}}';
    const USER_ROLE = 1;
    const USER_STATUS = 2;

    public function up()
    {
        $this->insert(self::PROPERTY, ['id' => self::USER_ROLE, 'name' => 'UserRole']);
        $this->insert(self::LOOKUP, ['name' => 'Администратор', 'code' => 1, 'property_id' => self::USER_ROLE, 'position' => 1]);
        $this->insert(self::LOOKUP, ['name' => 'Автор', 'code' => 2, 'property_id' => self::USER_ROLE, 'position' => 2]);

        $this->insert(self::PROPERTY, ['id' =>  self::USER_STATUS, 'name' => 'UserStatus']);
        $this->insert(self::LOOKUP, ['name' => 'Активен', 'code' => 1, 'property_id' => self::USER_STATUS, 'position' => 1]);
        $this->insert(self::LOOKUP, ['name' => 'Архив', 'code' => 2, 'property_id' => self::USER_STATUS, 'position' => 2]);
    }

    public function down()
    {
        $this->delete(self::LOOKUP, 'property_id=' . self::USER_ROLE);
        $this->delete(self::LOOKUP, 'property_id=' . self::USER_STATUS);
        $this->delete(self::PROPERTY, self::USER_ROLE);
        $this->delete(self::PROPERTY, self::USER_STATUS);
    }
}
