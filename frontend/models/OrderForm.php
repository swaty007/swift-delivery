<?php

namespace frontend\models;

use common\models\GoogleMaps;
use common\models\Log;
use common\models\OrderItem;
use common\models\Order;
use common\models\OrderQuery;
use common\models\Product;
use common\models\ProductOption;
use common\models\Supplier;
use common\models\Twilio;
use common\models\User;
use common\models\Zipcode;
use frontend\controllers\SiteController;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class OrderForm extends Model
{
    public $name;
    public $phone_number;

    public $zip;
    public $address;
    public $address_2;
    public $description;

    private $total;

    /**
     * @var Order
     */
    public $instance;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_number', 'name', 'zip', 'address'], 'required'],
            ['phone_number', 'trim'],
            ['phone_number', 'match', 'pattern' => '/^\+1\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{4}$/', 'message' => 'Incorrect phone number'],
            [['name'], 'string', 'max' => 50, 'min' => 2],
            [['address', 'address_2'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 200],
            ['zip', 'string'],
        ];
    }

    /**
     * @return bool|Order|null
     */
    public function createOrder()
    {
        if (!$this->validate()) {
            return null;
        }

        $gm = new GoogleMaps();

        $apiResult = $gm->getLatLng($this->address . ' ' . $this->address_2 . ' ' . $this->zip);

        if ($apiResult['success'] == false) {
            $this->addError('address', $apiResult['message']);
            return null;
        }

        if (Zipcode::isBlocked($this->zip)) {
            $this->addError('zip', 'Zipcode is not allowed');
            return null;
        }

        try {
            if (!($customer = User::getCustomer($this->phone_number))) {
                $customer = new User();
                $customer->phone_number = $this->phone_number;
                $customer->status = 0;
                $customer->username = $this->name . time();
                $customer->role = User::USER_ROLE_CUSTOMER;
                $customer->setPassword('');

                if (!$customer->save()) {
                    $this->printAndExit($customer->errors);
                }
            } else {
                $customer->username = $this->name . time();
                $customer->save();
            }

            $order = new Order();
            $order->description = $this->description;
            $order->customer_id = $customer->id;
            $order->zip = $this->zip;
            $order->address = $this->address;
            $order->address_2 = $this->address_2;
            $order->status = Order::ORDER_STATUS_NEW;


            do {
                $order->weblink = Yii::$app->security->generateRandomString(16);
            } while (Order::find()->where(['weblink' => $order->weblink])->count());


            if (!$order->save()) {
                $this->printAndExit($order->errors);
            }

            OrderQuery::createOrderQuery($order);

            $this->processOrderItems($order);

            $order->total = $this->total;

            if ($order->total == 0) {
                throw new \Exception("Wrong order");
            }

            $order->save();
            Log::orderLog($order->id, $customer->id, "Order created");

            $this->instance = $order;

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

            /*
            */

            return true;
        } catch (\Exception $e) {
            Order::deleteAll(['total' => 0, 'status' => Order::ORDER_STATUS_NEW]);
            return false;
        }
    }

    private function processOrderItems(Order $order)
    {
        $cart = SiteController::getCart();
        $orderTotal = 0;

        foreach ($cart as $item) {
            $option = new OrderItem();
            $option->order_id = $order->id;
            $option->count = $item['count'];
            $option->description = $item['name'];
            $option->item_price = $item['price'];
            $option->product_item_id = ProductOption::findOne(['id' => $item['id']])->product_id;
            $option->total_price = $item['price'] * $item['count'];
            $orderTotal += $option->total_price;
            if (!$option->save()) {
                $this->printAndExit($option->errors);
            }
        }

        $this->total = $orderTotal;

        Yii::$app->session->set('cart', '{}');
        return true;
    }

    private function printAndExit($d)
    {
        return false;
    }
}
