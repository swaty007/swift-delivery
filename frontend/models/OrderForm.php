<?php
namespace frontend\models;

use common\models\OrderItem;
use common\models\Order;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */

class OrderForm extends Model
{
    public $supplier_id;
    public $customer_id;
    public $name;
    public $phone_number;

    public $zip;
    public $address;
    public $address_2;
    public $description;
    public $items;

    public $web_url;//тут проверь
    public $weblink;//тут проверь


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['supplier_id', 'zip', 'address', 'name', 'terms', 'product_name'], 'required'],
//            ['supplier_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => 'common\models\User'],
//            ['supplier_id', 'unique', 'targetAttribute' => 'supplier_id', 'targetClass' => 'common\models\Supplier'],
//            [['name', 'product_name'], 'string', 'max' => 50, 'min' => 2],
//            [['address', 'address_2'], 'string', 'max' => 60],
//            [['items', 'plan'], 'safe'],
//            [['items', 'plan'], 'required'],
//            [['web_url'], 'url'],
//            ['terms', 'compare', 'compareValue' => 1, 'type' => 'number', 'operator' => '==', 'message' => 'Please, accept terms of use.'],
//            [['logo', 'product_image'], 'file', 'extensions' => 'png, jpg'],
//            ['zip', 'exist', 'targetAttribute' => 'zipcode', 'targetClass' => 'common\models\Zipcode', 'message' => 'This zip is not supported.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function confirm()
    {
        if (!$this->validate()) {
            return null;
        }

        try {
            $order = new Order();

            $order->supplier_id = $this->supplier_id;
            $order->customer_id = $this->customer_id;
            $order->name = $this->name;//добавь в базу ордер нейм, проверки добавил макс 80 символов
            $order->zip = $this->zip;
            $order->address = $this->address;
            $order->address_2 = $this->address_2;
            $order->weblink = $this->weblink;//тут проверь

            $order->save();
            $this->processOrderItems($order);

            return true;
        } catch(\Exception $e) {
//            Order::deleteAll(['order_id' => $this->supplier_id]); //тут проверь
//            OrderItem::deleteAll(['order_id' => $this->supplier_id]);//тут проверь
            return false;
        }
    }

    private function processOrderItems(Order $order) {
        $allowedOrderItemsIds = array_keys(ArrayHelper::index(Yii::$app->params['subscribePlans'], 'value'));//тут проверь

        foreach ($this->items as $orderItem) {
            if(!in_array($orderItem, $allowedOrderItemsIds)) {
                continue ;
            }

            $relation = new OrderItem();
            $relation->order_id = 0;//$order->null;
            $relation->product_item_id = 0;//$order->null;
            $relation->count = 0;//$order->null;
            $relation->description = 0;//$order->null;
            $relation->item_price = 0;//$order->null;
            $relation->total_price = 0;//$order->null;
            $relation->save();
        }
    }

}
