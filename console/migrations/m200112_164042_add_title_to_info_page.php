<?php

use yii\db\Migration;

/**
 * Class m200112_164042_add_title_to_info_page
 */
class m200112_164042_add_title_to_info_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('info_page', 'title', $this->string(100)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('info_page', 'title');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200112_164042_add_title_to_info_page cannot be reverted.\n";

        return false;
    }
    */
}
