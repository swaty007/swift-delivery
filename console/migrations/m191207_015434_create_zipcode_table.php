<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%zipcode}}`.
 */
class m191207_015434_create_zipcode_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%zipcode}}', [
            'id' => $this->primaryKey(),
            'is_active' => $this->integer(11)->defaultValue(1),
            'zipcode' => $this->string(32)->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%zipcode}}');
    }
}
