<?php

namespace console\controllers;

use common\models\GoogleMaps;
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
        for ($i = 0; $i < 6; $i++) {
            $data = OrderQuery::find()
                ->where(['order' => 0])
                ->all();

            print("START PROCESS\n " . date("H:i:s d-m-Y") . "\n");

            if (!count($data)) {
                print("NO ORDERS IN PROGRESS\n");
                foreach(Order::findAll(['status' => Order::ORDER_STATUS_NEW]) as $order) {
                    $user = User::findOne($order->customer_id);
                    $messageCustomer = "Unfortunately, DC delivery services were unable to respond. Please try submitting your order again soon.";
                    Twilio::sendSms($user->phone_number, $messageCustomer);
                    $order->status = Order::ORDER_STATUS_CANCELLED;
                    $order->save();
                }
            } else {
                print("QUERY ITEMS: " . count($data) ."\n");
                foreach ($data as $item) {
                    $supplierId = $item->supplier_id;
                    $orderId = $item->order_id;

                    if ($item->time_start_query == 0) {
                        print("NEXT SUPPLIER\n");
                        $item->time_start_query = time();
                        $item->save();

                        $order = Order::findOne($orderId);

                        $supplier = Supplier::find()->where(['id' => $supplierId])->one();
                        $supplierUser = User::find()->where(['id' => $supplier->supplier_id])->one();

                        $gm = new GoogleMaps();

                        $supplierLatlng = $gm->getLatLng($supplier->address . ' ' . $supplier->address_2. ' ' . $supplier->zip);
                        $customerLatlng = $gm->getLatLng($order->address . ' ' . $order->address_2. ' ' . $supplier->zip);
                        $distance = $gm->getDistanceMatrix($supplierLatlng, $customerLatlng);

                        $messageSupplier = "You have a new order available for the next 2 minutes until "
                            . date ('g:i:s A', time() + 120)
                            ." Click to View/Accept/Decline this order "
                            . \Yii::$app->params['webProjectUrl']
                            . '/supplier/show-order?l='
                            . $order->weblink;

                        if ($distance['success'] == true) {
                            $messageSupplier .= ' ETA: '.$distance['duration'];
                        } else {
                            $messageSupplier .= ' ETA: 30 mins';
                        }


                        Twilio::sendSms($supplierUser->phone_number, $messageSupplier);
                    }

                    if (($item->time_start_query + \Yii::$app->params['timeToTakeOrder']) <= time()) {
                        $item->delete();
                        print("EXPIRED\n");

                        if (!OrderQuery::find()->where(['order_id' => $orderId])->count()) {
                            $order = Order::findOne($orderId);
                            $user = User::findOne($order->customer_id);
                            $messageCustomer = "Unfortunately, DC delivery services were unable to respond. Please try submitting your order again soon.";
                            Twilio::sendSms($user->phone_number, $messageCustomer);
                            Order::updateAll(['status' => Order::ORDER_STATUS_CANCELLED], ['id' => $orderId]);
                        } else {
                            OrderQuery::updateAllCounters(['order' => -1],['order_id' => $orderId]);
                        }
                    }
                }
            }
            if ($i < 6) {
                sleep(10);
            }
        }
    }
}
