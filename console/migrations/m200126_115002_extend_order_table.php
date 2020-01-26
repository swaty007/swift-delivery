<?php

use yii\db\Migration;

/**
 * Class m200126_115002_extend_order_table
 */
class m200126_115002_extend_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'delivery_duration', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'delivery_duration');
    }
}
