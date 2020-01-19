<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%info_page}}`.
 */
class m200112_161003_create_info_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%info_page}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(100)->unique()->notNull(),
            'body' => ' LONGTEXT',
            'is_active' => $this->integer(11)->defaultValue(1)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%info_page}}');
    }
}
