<?php

use yii\db\Migration;

/**
 * Class m200402_210005_extend_prod_table
 */
class m200402_210005_extend_prod_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'note', $this->string(512)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'note');
    }
}
