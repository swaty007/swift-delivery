<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier}}`.
 */
class m190916_221746_create_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer()->notNull(),
            'name' => $this->string(100),
            'logo' => $this->string(256),
            'zip' => $this->string(20),
            'address' => $this->string(80),
            'address_2' => $this->string(80),
            'description' => $this->string(200),
            'product_name' => $this->string(100),
            'product_image' => $this->string(256),
            'status' => $this->integer()->defaultValue(0),
            'is_active' => $this->integer()->defaultValue(0),
            'latitude' => $this->float()->defaultValue(0),
            'longitude' => $this->float()->defaultValue(0),
            'date_created' => $this->dateTime()->defaultExpression('NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplier}}');
    }
}
