<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%params}}`.
 */
class m200204_211102_create_params_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%param}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(20),
            'label' => $this->string(50),
            'value' => $this->string(512),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%param}}');
    }
}
