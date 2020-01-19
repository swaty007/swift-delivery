<?php

namespace frontend\models;

use common\models\OrderItem;
use common\models\Order;
use common\models\Product;
use common\models\ProductOption;
use common\models\User;
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
            ['phone_number', 'match', 'pattern' => '/^\+1\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Incorrect phone number'],
            [['name'], 'string', 'max' => 50, 'min' => 2],
            [['address', 'address_2'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 200],
            ['zip', 'string'],
            // TODO: ask if we need this
            ////  ['zip', 'exist', 'targetAttribute' => 'zipcode', 'targetClass' => 'common\models\Zipcode', 'message' => 'This zip is not supported.'],
        ];
    }

    /**
     * @return bool|Order|null
     */
    public function createOrder()
    {
        if (!$this->validate()) {
            var_dump($this->errors);
            exit;
            return null;
        }

        try {
            if (!($customer = User::getCustomer($this->phone_number))) {
                $customer = new User();
                $customer->phone_number = $this->phone_number;
                $customer->status = 0;
                $customer->username = $this->phone_number;
                $customer->role = User::USER_ROLE_CUSTOMER;
                $customer->setPassword('');

                if (!$customer->save()) {
                    $this->printAndExit($customer->errors);
                }
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

            $this->processOrderItems($order);

            $this->instance = $order;
            return true;
        } catch (\Exception $e) {
            //User::deleteAll(['phone_number' => $this->phone_number]);


            var_dump($this->errors);
            var_dump($e);
            exit;
            return false;
        }
    }

    private function processOrderItems(Order $order)
    {
        $cart = SiteController::getCart();

        foreach ($cart as $item) {
            $option = new OrderItem();
            $option->order_id = $order->id;
            $option->count = $item['count'];
            $option->description = $item['name'];
            $option->item_price = $item['price'];
            $option->product_item_id = ProductOption::findOne(['id' => $item['id']])->product_id;
            $option->total_price = $item['price'] * $item['count'];
            if (!$option->save()) {
                $this->printAndExit($option->errors);
            }
        }

        Yii::$app->session->set('cart', '{}');
        return true;
    }

    private function printAndExit($d) {
        echo '<pre>';
        var_dump($d);
        exit;
    }
}