<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_query}}`.
 */
class m200327_201931_create_order_query_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_query}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'supplier_id' => $this->integer()->notNull(),
            'order' => $this->integer(),
            'time_start_query' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_query}}');
    }
}
