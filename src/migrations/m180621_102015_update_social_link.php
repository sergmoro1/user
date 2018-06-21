<?php

use yii\db\Migration;

/**
 * Class m180621_102015_update_social_link
 */
class m180621_102015_update_social_link extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
		$this->addColumn('{{%social_link}}', 'avatar', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('{{%social_link}}', 'avatar');
    }
}
