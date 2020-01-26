<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%address_latlng}}`.
 */
class m200126_102506_create_address_latlng_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%address_latlng}}', [
            'id' => $this->primaryKey(),
            'address' => $this->string(255),
            'latlng' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%address_latlng}}');
    }
}
