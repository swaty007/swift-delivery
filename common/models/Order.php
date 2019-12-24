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
    const ORDER_STATUS_NEW = 0;
    const ORDER_STATUS_IN_PROGRESS = 1;
    const ORDER_STATUS_DELIVER_NEAR_PLACE = 2;
    const ORDER_STATUS_DELIVER_AT_PLACE = 3;
    const ORDER_STATUS_COMPLETE = 10;
    const ORDER_STATUS_CANCELLED_BY_SUPPLIER = 20;
    const ORDER_STATUS_CANCELLED_BY_DELIVER = 21;
    const ORDER_STATUS_CANCELLED_BY_CUSTOMER = 22;
    const ORDER_STATUS_CANCELLED = 23;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer() {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier() {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
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
            [['address', 'address_2'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 200],
            [['weblink'], 'string', 'max' => 100],
        ];
    }

    public function getStatusDescription() {
        $statuses = [
            self::ORDER_STATUS_NEW => 'Waiting for supplier',
            self::ORDER_STATUS_IN_PROGRESS => 'Making delivery',
            self::ORDER_STATUS_DELIVER_NEAR_PLACE => 'Deliver near place',
            self::ORDER_STATUS_DELIVER_AT_PLACE => 'Deliver at place',
            self::ORDER_STATUS_COMPLETE => 'Complete',
            self::ORDER_STATUS_CANCELLED_BY_SUPPLIER => 'Canceled by supplier',
            self::ORDER_STATUS_CANCELLED_BY_DELIVER => 'Canceled by deliver',
            self::ORDER_STATUS_CANCELLED_BY_CUSTOMER => 'Canceled by customer',
            self::ORDER_STATUS_CANCELLED => 'Canceled',
        ];

        return $statuses[$this->status];
    }

    public function getStatusText() {
        $statuses = [
            self::ORDER_STATUS_NEW => 'Searching Delivery Companies Nearby...',
            self::ORDER_STATUS_IN_PROGRESS => 'Delivery in progress',
            self::ORDER_STATUS_DELIVER_NEAR_PLACE => 'Deliver near place',
            self::ORDER_STATUS_DELIVER_AT_PLACE => 'Deliver at place',
            self::ORDER_STATUS_COMPLETE => 'Complete',
            self::ORDER_STATUS_CANCELLED_BY_SUPPLIER => 'Canceled by supplier',
            self::ORDER_STATUS_CANCELLED_BY_DELIVER => 'Canceled by deliver',
            self::ORDER_STATUS_CANCELLED_BY_CUSTOMER => 'Canceled by customer',
            self::ORDER_STATUS_CANCELLED => 'Canceled',
        ];

        return $statuses[$this->status];
    }

    public static function getStatusTextFromStatus(int $status) {
        $dummy = new self;
        $dummy->status = $status;
        return $dummy->getStatusText();
    }

    public static function getStatusDescriptionFromStatus(int $status) {
        $dummy = new self;
        $dummy->status = $status;
        return $dummy->getStatusDescription();
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
