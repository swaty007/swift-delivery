<?php use yii\helpers\Url;
?>

<?php if ($order->status == \common\models\Order::ORDER_STATUS_CANCELLED_BY_CUSTOMER):?>
    <div class="modal modal--full-screen" id="cancel_order_by_supplier" style="display:block;">
        <div class="modal__wrapper">
            <div class="modal__container container">
                <div class="modal__close"></div>
                <div class="modal__header">
                    <br>
                    <p class="text text--blue">Sorry, customer canceled an order</p>
                    <br>
                    <p class="text text--blue">You can call to the customer</p>
                    <br>
                    <a href="tel:<?=preg_replace( '/[^0-9]/', '', \common\models\User::findById($order->customer->supplier_id)->phone_number );?>">
                        <?= \common\models\User::findById($order->customer->supplier_id)->phone_number ;?>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>

<?php if ($order->status == \common\models\Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER || $order->status == \common\models\Order::ORDER_STATUS_CANCELLED_BY_DELIVER):?>
<div class="modal modal--full-screen" id="cancel_order_by_customer" style="display:block;">
    <div class="modal__wrapper">
        <div class="modal__container container">
            <div class="modal__close"></div>
            <div class="modal__header">
                <br>
                <p class="text text--blue">
                    Please call the customer to cancel the order.
                </p>
                <br>
                <p class="text text--blue">
                    Tell them to go back to the homepage & start a new order
                </p>
                <br>
                <a href="tel:<?=preg_replace( '/[^0-9]/', '', \common\models\User::findById($order->customer->supplier_id)->phone_number );?>">
                    <?= \common\models\User::findById($order->customer->supplier_id)->phone_number ;?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif;?>