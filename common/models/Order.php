<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $supplier_id
 * @property string $zip
 * @property string $name
 * @property string $address
 * @property string $address_2
 * @property string $description
 * @property double $latitude
 * @property double $longitude
 * @property string $weblink
 * @property int $status
 * @property string $created_at
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'supplier_id', 'status'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['created_at'], 'safe'],
            [['zip'], 'string', 'max' => 20],
            [['address', 'address_2', 'name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 200],
            [['weblink'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'supplier_id' => 'Supplier ID',
            'zip' => 'Zip',
            'address' => 'Address',
            'address_2' => 'Address 2',
            'description' => 'Description',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'weblink' => 'Weblink',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
