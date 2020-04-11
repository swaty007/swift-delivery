<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php \yii\widgets\Pjax::begin(['id' => 'supplier_order',
    'options' => [
        'class' => '',
        'tag' => 'div'
    ]]); ?>
<?php if($order->status === common\models\Order::ORDER_STATUS_NEW):?>
    <div class="alert alert--black">
        <div class="container flex-center">
            <p class="text text--small">
                New delivery request!  Order expires in  <strong class="text--white"><?=$timeToTake;?>s</strong>
            </p>
        </div>
    </div>
<?php endif;?>

<section class="supplier-order">
    <div class="container">
        <br>
        <h4 class="supplier-cab__table-content--title text--xs">
            Company: <span class="text--regular"><?=$order->supplier->name ?></span>
        </h4>
        <h4 class="supplier-cab__table-content--title text--xs">
            Order #: <a href="<?= Url::toRoute('/supplier/show-order?l=') . $order->weblink ?>" class="text--regular"><?= $order->weblink ?></a>
        </h4>
        <h4 class="supplier-cab__table-content--title text--xs">
            Delivering to:
            <span class="text--regular">
                                <?= $order->address ?>
                <?php if ($order->address_2): ?>
                    <?= $order->address_2 ?>
                <?php endif; ?>
                                / <?= $order->zip ?>
                            </span>
        </h4>
        <h4 class="supplier-cab__table-content--title text--xs">
            Zip Code: <span class="text--regular"><?= $order->zip ?></span>
        </h4>
        <br>
        <?php if(\common\models\AddressLatlng::tryGetAddressData($order->address . ' ' . $order->address_2 . ' ' . $order->zip) !== null):?>
            <img class="supplier-cab__map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$order->address?>&zoom=13&size=300x300&maptype=roadmap
&markers=color:green%7Clabel:D%7C<?=\common\models\AddressLatlng::tryGetAddressData($order->address . ' ' . $order->address_2 . ' ' . $order->zip)->latlng?>
&key=<?=Yii::$app->params['googleMapsApiKey']?>" alt="Map">
        <?php endif;?>

        <h4 class="text--xs">
            Order:
        </h4>
<!--        <h4 class="supplier-cab__table-content--title text--xs">-->
<!--            Customer name: <span class="text--regular">--><?//=$order->customer->username?><!--</span>-->
<!--        </h4>-->
<!--        <h4 class="supplier-cab__table-content--title text--xs">-->
<!--            Customer phone:-->
<!--            <a href="tel:--><?//=preg_replace( '/[^0-9]/', '', $order->customer->phone_number );?><!--" class="text--regular">-->
<!--                --><?//= $order->customer->phone_number;?>
<!--            </a>-->
<!--        </h4>-->
<!--        <h4 class="supplier-cab__table-content--title text--xs">-->
<!--            Customer name: <span class="text--regular">--><?//=$order->customer->username?><!--</span>-->
<!--        </h4>-->
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
            <?php if (!empty($order->supplier)):?>
                <p class="text--xs text--blue-opacity">
                    <strong>&nbsp;</strong>
                </p>
                <p class="text--xs">
                    <strong>Gift Product:</strong> <?=$order->supplier->product_name?>
                </p>
                <div class="text-center">
                    <?= Html::img(Yii::$app->params['webUploadsDir'].$order->supplier->product_image, ['class' => 'on-way__img']); ?>
                </div>
            <?php endif;?>

        </div>
        <?php if (!empty($order->description)):?>
            <blockquotes class="blockquotes text-left">
                <p class="text--small">
                    <strong>Delivery Notes: </strong><?=$order->description?>
                </p>
            </blockquotes>
        <?php else:;?>
            <br>
        <?php endif;?>

        <div class="row">
            <div class="col-sm-6">
        <?php if($order->status === common\models\Order::ORDER_STATUS_NEW):?>
            <a href="#" class="btn-sm main-btn main-btn--black main-btn--succes" data-direction="take-order" data-order-id="<?= $order->id ?>">
                Set ETA
            </a>
        <?php else: ?>
            <a href="<?=Url::toRoute(['supplier/index','complete' => $order->id]);?>" class="main-btn main-btn--black main-btn--succes">
                Complete order
            </a>
        <?php endif;?>
                <br>
            </div>
            <div class="col-sm-6">
                <a href="<?=Url::toRoute(['supplier/index','cancelSupplier' => $order->id]);?>" class="main-btn main-btn--red main-btn--decline">
                    Decline
                </a>
            </div>
        </div>
        <br>
        <br>

    </div>
    <div class="deliver__info">
        <div class="container">
            <div class="flex-center flex-center--between">
                <div class="deliver__info--content">
                    <br>
                    <p class="text text--white text--small"><strong><?=$order->customer->username;?></strong> is your customer!</p>
                    <br>
<!--                    --><?php //if($order->status !== common\models\Order::ORDER_STATUS_NEW):?>
<!--                        <p class="text">-->
<!--                            <a href="--><?//=Url::toRoute(['supplier/index','cancelSupplier' => $order->id]);?><!--" class="text text--red text--xs">-->
<!--                                --><?//= Html::img('@web/img/icon_cancel.svg', ['class' => '']) ?>
<!--                                Cancel Order-->
<!--                            </a>-->
<!--                        </p>-->
<!--                    --><?php //endif;?>
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
                    <a href="tel:<?=preg_replace( '/[^0-9]/', '', $order->customer->phone_number );?>">
                        <?= $order->customer->phone_number ;?>
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
                <a href="tel:<?=preg_replace( '/[^0-9]/', '', $order->customer->phone_number );?>">
                    <?= $order->customer->phone_number ;?>
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
  }, 1000)
</script>

<?php \yii\widgets\Pjax::end(); ?>
<div class="modal modal--full-screen modal--order" id="take_order">
    <div class="modal__wrapper">
        <div class="modal__container container">
            <div class="modal__close"></div>
            <div class="modal__time">
                Order expires in  <strong id="modal__time">50</strong>s
            </div>
            <div class="modal__header">
                <input type="hidden" id="modal_take_order_id">
                <h3 class="modal__title text--small text--blue">
                    What’s your ETA?
                </h3>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-6 col-sm-4 col-md-4 col-md-offset-2">
                            <select class="default-select" name="" id="modal_take_order_time_val">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                <option value="35">35</option>
                                <option value="40">40</option>
                                <option value="45">45</option>
                                <option value="50">50</option>
                                <option value="55">55</option>
                                <option value="60">60</option>
                            </select>
                            <!--                            <input id="modal_take_order_time_val" type="text" class="form-control">-->
                        </div>
                        <div class="col-xs-6 col-sm-8 col-md-4">
                            <p class="default-select default-select--empty modal__select--text text text--blue">minutes</p>
                            <!--                            <select class="default-select" name="" id="modal_take_order_time">-->
                            <!--                                <option value="min">minutes</option>-->
                            <!--                                <option value="hours">hours</option>-->
                            <!--                            </select>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal__body">
                <h3 class="modal__title text--small text--blue">
                    Who’s delivering?
                </h3>
                <div class="row">
                    <div class="form-group col-md-8 col-md-offset-2">
                        <input id="modal_take_order_name" type="text" class="form-control" placeholder="Enter first name here">
                    </div>
                </div>
                <div class="form-group">
                    <p class="text--small text--blue-opacity">
                        ETA: (by google maps from your office)
                    </p>
                    <p id="modal_take_order_duration" class="text--large text--bold text--red">

                    </p>
                </div>

                <button id="take_order_btn" class="main-btn main-btn--black w100">Accept Delivery!</button>
            </div>
            <div class="modal__success">
                <?= Html::img('@web/img/icon_accept_delivery.svg', ['class' => 'modal__success--img']); ?>
                <p class="sub-text text--white">
                    Delivery Accepted!
                    <br>
                    <span class="text--small text--white-opacity">Away you go!</span>
                </p>
            </div>
        </div>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <?php if (Yii::$app->session->getFlash('success') === 'Delivery Completed'): ?>
        <div class="modal modal--full-screen modal--order" data-autoremove="3000" id="modal_complete_order" style="display: block">
            <div class="modal--success modal__wrapper">
                <div class="modal__container container">
                    <div class="modal__success modal__success--black">
                        <?= Html::img('@web/img/icon_accept_delivery.svg', ['class' => 'modal__success--img']); ?>
                        <p class="sub-text text--white">
                            Delivery Completed!
                            <br>
                            <span class="text--small text--white-opacity">Who’s awesome? You are!</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>