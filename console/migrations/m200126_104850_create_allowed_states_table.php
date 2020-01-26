<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%allowed_states}}`.
 */
class m200126_104850_create_allowed_states_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%allowed_states}}', [
            'id' => $this->primaryKey(),
            'state_name' => $this->string(155),
            'is_active' => $this->integer()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%allowed_states}}');
    }
}
