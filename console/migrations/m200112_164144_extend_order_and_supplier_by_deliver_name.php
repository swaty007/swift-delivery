<?php

use yii\db\Migration;

/**
 * Class m200112_164144_extend_order_and_supplier_by_deliver_name
 */
class m200112_164144_extend_order_and_supplier_by_deliver_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'deliver_name', $this->string(100)->defaultValue(''));
        $this->addColumn('supplier', 'default_deliver_name', $this->string(100)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'deliver_name');
        $this->dropColumn('supplier', 'default_deliver_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200112_164144_extend_order_and_supplier_by_deliver_name cannot be reverted.\n";

        return false;
    }
    */
}
