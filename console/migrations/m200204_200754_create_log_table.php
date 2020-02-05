<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m200204_200754_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11),
            'user_id' => $this->integer(11),
            'text' => $this->string(255),
            'type' => $this->string(50),
            'date' => $this->dateTime()->defaultExpression('NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
