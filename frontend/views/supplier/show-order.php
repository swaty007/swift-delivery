<?php use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php \yii\widgets\Pjax::begin(['id' => 'supplier_order',
    'options' => [
        'class' => '',
        'tag' => 'div'
    ]]); ?>
<section class="supplier-order">
    <div class="container">
        <?php if(\common\models\AddressLatlng::tryGetAddressData($order->address . ' ' . $order->address_2) !== null):?>
            <img class="supplier-cab__map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$order->address?>&zoom=13&size=300x300&maptype=roadmap
&markers=color:green%7Clabel:D%7C<?=\common\models\AddressLatlng::tryGetAddressData($order->address . ' ' . $order->address_2)->latlng?>
&key=<?=Yii::$app->params['googleMapsApiKey']?>" alt="Map">
        <?php endif;?>

        <h4 class="text--xs">
            Order:
        </h4>
        <div class="card__wrap">
            <?php foreach ($order->orderItems as $key => $gift): ?>
                <div class="card__item">
                    <div class="card__item--left">
                        <p class="text--xs text--blue-opacity">
                            <strong>
                                Flower | <span class="text--blue">$<?= $gift->item_price ?></span>
                            </strong>
                        </p>
                        <p class="card__text text--blue-opacity">
                            <?= $gift->description ?>
                        </p>
                        <p class="card__text text--blue-opacity">
                            <strong>
                                Quantity: <span><?= $gift->count ?></span>
                            </strong>
                        </p>
                    </div>
                    <div class="card__item--right">
                        <p class="text--xs text--blue">
                            <strong>
                                $<?= number_format($gift->total_price, 2); ?>
                            </strong>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="card__item card__item--total">
                <div class="card__item--left">
                    <p class="text--small text--blue-opacity">
                        <strong>Total</strong>
                    </p>
                </div>
                <div class="card__item--right">
                    <p class="text--small text--red">
                        <strong>
                            $<?= number_format($order->total, 2); ?>
                        </strong>
                    </p>
                </div>
            </div>
            <p class="text--xs text--blue-opacity">
                <strong>&nbsp;</strong>
            </p>
            <p class="text--xs text--blue-opacity">
                Gift Product: <?=$order->supplier->product_name?>
            </p>
            <?= Html::img(Yii::$app->params['webUploadsDir'].$order->supplier->product_image, ['class' => 'on-way__img']); ?>
        </div>

        <blockquotes class="blockquotes text-left">
            <p class="text--small">
                <strong>Delivery Notes: </strong>Please dont ring the doorbell... Woofuss 🐶 hates it!
            </p>
        </blockquotes>
        <a href="<?=Url::toRoute(['supplier/index','complete' => $order->id]);?>" class="main-btn main-btn--blue">
            Complete order
        </a>
        <br>
        <br>
        <br>

    </div>
    <div class="deliver__info">
        <div class="container">
            <div class="flex-center flex-center--between">
                <div class="deliver__info--content">
                    <p class="text text--white text--small"><strong><?=$order->customer->username;?></strong> is your customer!</p>
                    <p class="text">
                        <a href="<?=Url::toRoute(['supplier/index','cancelSupplier' => $order->id]);?>" class="text text--red text--xs">
                            <?= Html::img('@web/img/icon_cancel.svg', ['class' => '']) ?>
                            Cancel Order
                        </a>
                    </p>
                </div>
                <div class="deliver__info--icons flex-center">
                    <a href="tel:<?=preg_replace( '/[^0-9]/', '', $order->customer->phone_number );?>" class="deliver__call"></a>
                    <a href="sms:<?=preg_replace( '/[^0-9]/', '', $order->customer->phone_number );?>" class="deliver__sms"></a>
                </div>
            </div>
        </div>
    </div>
</section>



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

<script>
  clearInterval(intervarPjax)
  var intervarPjax = setInterval(function () {
    if (typeof $.pjax !== 'undefined') {
      if (!$('.modal').is(':visible')) {
        // $.pjax.reload({container: "#supplier_order"});
      }
    }
  }, 5000)
</script>

<?php \yii\widgets\Pjax::end(); ?>
