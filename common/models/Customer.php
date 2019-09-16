<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $customer_name
 * @property string $phone_number
 * @property int $rating
 * @property int $is_blocked
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_name', 'phone_number'], 'required'],
            [['rating', 'is_blocked'], 'integer'],
            [['customer_name'], 'string', 'max' => 80],
            [['phone_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_name' => 'Customer Name',
            'phone_number' => 'Phone Number',
            'rating' => 'Rating',
            'is_blocked' => 'Is Blocked',
        ];
    }
}
