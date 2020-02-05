<?php

use yii\db\Migration;

/**
 * Class m200204_214400_extend_log_table
 */
class m200204_214400_extend_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('log', 'receiver', $this->string(200)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('log', 'receiver');
    }
}
