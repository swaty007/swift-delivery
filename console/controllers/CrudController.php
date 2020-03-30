<?php

namespace console\controllers;

use common\models\Message;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderQuery;
use common\models\Product;
use common\models\Supplier;
use common\models\Twilio;
use common\models\User;
use yii\console\Controller;

/**
 * CrudController
 */
class CrudController extends Controller
{
    public function actionUnsubscribe()
    {
        $suppliers = Supplier::find()->where(['<', 'subscribe_ends', date('Y-m-d H:i:s', time())])->andWhere(['is_active' => 1])->all();

        foreach ($suppliers as $supplier) {
            $number = User::find()->where(['id' => $supplier->supplier_id])->one()->phone_number;
            Twilio::sendSms($number, Message::getText('delivery_plan_requested_sms'));
            $supplier->is_active = 0;
            $supplier->save();
        }
    }

    public function actionProcessOrderQuery()
    {
        for ($i = 0; $i < 5; $i++) {
            $data = OrderQuery::find()
                ->where(['order' => 0])
                ->all();

            print("START PROCESS\n");
            print("QUERY ITEMS: " . count($data) ."\n");
            foreach ($data as $item) {
                $supplierId = $item->supplier_id;
                $orderId = $item->order_id;

                if ($item->time_start_query == 0) {
                    print("NEXT SUPPLIER\n");
                    $item->time_start_query = time();
                    $item->save();

                    $order = Order::findOne($orderId);

                    $products = [];
                    $rawProducts = [];

                    foreach (OrderItem::findAll(['order_id' => $order->id]) as $orderItem) {
                        $productName = Product::find()->where(['id' => $orderItem->product_item_id])->one()->name;
                        if (!isset($rawProducts[$productName])) {
                            $rawProducts[$productName] = $orderItem->count;
                        } else {
                            $rawProducts[$productName] += $orderItem->count;
                        }
                    }

                    foreach ($rawProducts as $name => $count) {
                        $products[] = $count . ' ' . $name;
                    }

                    $messageSupplier = "New order available for $$order->total to delivery: " . implode(' & ', $products);

                    $supplier = Supplier::find()->where(['id' => $supplierId])->one();
                    $supplierUser = User::find()->where(['id' => $supplier->supplier_id])->one();

                    Twilio::sendSms($supplierUser->phone_number, $messageSupplier);
                }

                if (($item->time_start_query + \Yii::$app->params['timeToTakeOrder']) <= time()) {
                    $item->delete();
                    print("EXPIRED\n");

                    if (!OrderQuery::find()->where(['order_id' => $orderId])->count()) {
                        $order = Order::findOne($orderId);
                        $user = User::findOne($order->customer_id);
                        $messageCustomer = "Unfortunatly, nobody takes your order. Please, try again later.";
                        Twilio::sendSms($user->phone_number, $messageCustomer);
                        Order::updateAll(['status' => Order::ORDER_STATUS_CANCELLED], ['id' => $orderId]);
                    } else {
                        OrderQuery::updateAllCounters(['order' => -1],['order_id' => $orderId]);
                    }
                }
            }

            sleep(10);
        }
    }
}
