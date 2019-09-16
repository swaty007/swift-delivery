<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_option}}`.
 */
class m190916_221024_create_product_option_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_option}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'name' => $this->string(100),
            'price' => $this->decimal(10,2),
            'order' => $this->integer()->defaultValue(0),
            'is_active' => $this->integer()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_option}}');
    }
}
