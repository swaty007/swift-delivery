<?php

use yii\db\Migration;

/**
 * Class m191215_154325_extend_order_table
 */
class m191215_154325_extend_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'weblink', $this->string(100)->defaultValue(null));
        $this->addColumn('order', 'status', $this->integer(11)->defaultValue(0));
        $this->addColumn('order', 'created_at', $this->dateTime()->defaultExpression('NOW()'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'weblink');
        $this->dropColumn('order', 'status');
        $this->dropColumn('order', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191215_154325_extend_order_table cannot be reverted.\n";

        return false;
    }
    */
}
