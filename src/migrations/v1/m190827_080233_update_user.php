<?php

use yii\db\Migration;

/**
 * Class m190827_080233_update_user
 */
class m190827_080233_update_user extends Migration
{
    private const TABLE_USER = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(static::TABLE_USER, 'name', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn(static::TABLE_USER, 'username', 'name');
    }
}
