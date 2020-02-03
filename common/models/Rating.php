<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property int $id
 * @property int $rating
 * @property int $is_friendly
 * @property int $is_fulfilled
 * @property int $is_on_time
 * @property int $would_use_again
 * @property int $supplier_id
 * @property int $order_id
 * @property string $comment
 */
class Rating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating', 'is_friendly', 'is_fulfilled', 'is_on_time', 'would_use_again', 'supplier_id', 'order_id'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rating' => 'Rating',
            'is_friendly' => 'Is Friendly',
            'is_fulfilled' => 'Is Fulfilled',
            'is_on_time' => 'Is On Time',
            'would_use_again' => 'Would Use Again',
            'supplier_id' => 'Supplier ID',
            'order_id' => 'Order ID',
            'comment' => 'Comment',
        ];
    }
}
