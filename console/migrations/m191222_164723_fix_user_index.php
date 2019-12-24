<?php

use yii\db\Migration;

/**
 * Class m191222_164723_fix_user_index
 */
class m191222_164723_fix_user_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('phone_number', '{{%user}}');
        $this->createIndex('phone_number-status', '{{%user}}', ['phone_number', 'status'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('phone_number-status', '{{%user}}');
        $this->createIndex('phone_number', '{{%user}}', 'phone_number', true);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191222_164723_fix_user_index cannot be reverted.\n";

        return false;
    }
    */
}
