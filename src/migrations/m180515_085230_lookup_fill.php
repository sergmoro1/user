<?php

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 */
class m180515_085230_lookup_fill extends Migration
{
    public function up()
    {
        $this->insert('{{%lookup}}', ['name' => 'Комментатор', 'code' => 3, 'type' => 'UserRole', 'position' => 3]);
    }

    public function down()
    {
        $this->delete('{{%lookup}}', 'type=:type AND code=3', [':type' => 'UserRole']);
    }
}
