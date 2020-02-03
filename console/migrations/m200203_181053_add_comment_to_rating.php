<?php

use yii\db\Migration;

/**
 * Class m200203_181053_add_comment_to_rating
 */
class m200203_181053_add_comment_to_rating extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('rating', 'comment', $this->string(255)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('rating', 'comment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200203_181053_add_comment_to_rating cannot be reverted.\n";

        return false;
    }
    */
}
