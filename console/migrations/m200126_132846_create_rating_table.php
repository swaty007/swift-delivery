<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rating}}`.
 */
class m200126_132846_create_rating_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rating}}', [
            'id' => $this->primaryKey(),
            'rating' => $this->integer(),
            'is_friendly' => $this->integer(11)->defaultValue(0),
            'is_fulfilled' => $this->integer(11)->defaultValue(0),
            'is_on_time' => $this->integer(11)->defaultValue(0),
            'would_use_again' => $this->integer(11)->defaultValue(0),
            'supplier_id' => $this->integer(11),
            'order_id' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rating}}');
    }
}
