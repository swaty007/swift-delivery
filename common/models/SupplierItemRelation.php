<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "supplier_item_rel".
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $item_id
 */
class SupplierItemRelation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_item_rel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id', 'item_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'item_id' => 'Item ID',
        ];
    }
}
