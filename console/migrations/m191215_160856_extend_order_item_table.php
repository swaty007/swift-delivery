<?php

use yii\db\Migration;

/**
 * Class m191215_160856_extend_order_item_table
 */
class m191215_160856_extend_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order_item', 'description', $this->string(200)->defaultValue(null));
        $this->addColumn('order_item', 'item_price', $this->decimal(12,2)->defaultValue(0));
        $this->addColumn('order_item', 'total_price', $this->decimal(12,2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order_item', 'description');
        $this->dropColumn('order_item', 'item_price');
        $this->dropColumn('order_item', 'total_price');
    }
}
