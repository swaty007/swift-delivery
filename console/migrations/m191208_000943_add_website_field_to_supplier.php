<?php

use yii\db\Migration;

/**
 * Class m191208_000943_add_website_field_to_supplier
 */
class m191208_000943_add_website_field_to_supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('supplier', 'website', $this->string(128)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('supplier', 'website');
    }
}
