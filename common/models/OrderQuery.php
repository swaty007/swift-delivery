<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_query".
 *
 * @property int $id
 * @property int $order_id
 * @property int $supplier_id
 * @property int $order
 * @property int $time_start_query
 */
class OrderQuery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'supplier_id'], 'required'],
            [['order_id', 'supplier_id', 'order', 'time_start_query'], 'integer'],
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
            'supplier_id' => 'Supplier ID',
            'order' => 'Order',
            'time_start_query' => 'Time Start Query',
        ];
    }

    static public function createOrderQuery(Order $order) {
        self::deleteAll(['order_id' => $order->id]);

        $query = [];
        $orderAddressData = AddressLatlng::tryGetAddressData($order->address . ' ' . $order->address_2 . ' ' . $order->zip);

        if (is_null($orderAddressData)) {
            return false;
        }

        $gm = new GoogleMaps();

        foreach (Supplier::find()->where(['is_active' => 1])->all() as $supplier) {
            if (!$supplier->isAllowedToTakeOrder()) {
                var_dump("123");
                exit;
                continue ;
            }

            $supplierAddressData = AddressLatlng::tryGetAddressData($supplier->address . ' ' . $supplier->address_2 . ' ' . $supplier->zip);

            if (is_null($supplierAddressData)) {
                var_dump("321");
                exit;
                continue ;
            }

            $distanceData = $gm->getDistanceMatrix($orderAddressData, $supplierAddressData);

            if ($distanceData && isset($distanceData['distance'])) {
                $queryData = [
                    'supplier_id' => $supplier->id,
                    'distance' => $distanceData['distance']
                ];

                $query[] = $queryData;
            }
        }

        if (!count($query)) {
            return false;
        }
        shuffle($query);
        usort($query, ['common\models\OrderQuery', 'sortByDistance']);
        foreach ($query AS $key => $item) {
            $entity = new self;
            $entity->order_id = $order->id;
            $entity->order = $key;
            $entity->supplier_id = $item['supplier_id'];
            $entity->time_start_query = 0;
            $entity->save();
        }

        return true;
    }

    function sortByDistance($a, $b)
    {
        $ad = $a['distance'];
        $bd = $b['distance'];

        if ($ad == $bd) {
            return 0;
        }

        return ($ad > $bd) ? 1 : -1;
    }
}
