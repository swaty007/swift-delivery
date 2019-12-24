<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_item_id
 * @property int $count
 * @property string $description
 * @property string $item_price
 * @property string $total_price
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_item_id'], 'required'],
            [['order_id', 'product_item_id', 'count'], 'integer'],
            [['item_price', 'total_price'], 'number'],
            [['description'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_item_id' => 'Product Item ID',
            'count' => 'Count',
            'description' => 'Description',
            'item_price' => 'Item Price',
            'total_price' => 'Total Price',
        ];
    }

    private function getProduct() {
        return $this->hasOne(Product::class, ['id' => 'product_item_id']);
    }
}
