<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m190916_221040_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'supplier_id' => $this->integer(),
            'zip' => $this->string(20),
            'address' => $this->string(80),
            'address_2' => $this->string(80),
            'description' => $this->string(600),
            'latitude' => $this->float()->defaultValue(0),
            'longitude' => $this->float()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
    }
}
