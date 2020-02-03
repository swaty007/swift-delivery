<?php

use yii\db\Migration;

/**
 * Class m200126_125947_create_add_total_to_order
 */
class m200126_125947_create_add_total_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'total', $this->decimal(12,2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'total');
    }
}
