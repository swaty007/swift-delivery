<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier_item_rel}}`.
 */
class m191207_171315_create_supplier_item_rel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier_item_rel}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer(),
            'item_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplier_item_rel}}');
    }
}
