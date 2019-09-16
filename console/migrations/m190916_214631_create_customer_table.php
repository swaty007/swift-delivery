<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m190916_214631_create_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'customer_name' => $this->string(80)->notNull(),
            'phone_number' => $this->string(20)->notNull(),
            'rating' => $this->integer()->defaultValue(0),
            'is_blocked' => $this->integer()->defaultValue(0),
            'date_created' => $this->dateTime()->defaultExpression('NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer}}');
    }
}
