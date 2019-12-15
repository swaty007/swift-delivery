<?php

use yii\db\Migration;

/**
 * Class m191215_163725_extend_supplier_table
 */
class m191215_163725_extend_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('supplier', 'subscribe_ends', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('supplier', 'subscribe_ends');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191215_163725_extend_supplier_table cannot be reverted.\n";

        return false;
    }
    */
}
