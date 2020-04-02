<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="bg--black on-way">
    <div class="confirm-supplier__container confirm-supplier__container--black text-center">
        <div class="container">
            <h1 class="text--white title confirm-supplier__title">
                <?= $order->getStatusText(); ?>
            </h1>

                <?= Html::img('@web/img/icon_congratulation.svg', ['class' => 'confirm-supplier__img']); ?>
        </div>
    </div>
    <div class="container text-center">

            <h2 class="on-way__title text--white-opacity">
                Estimated delivery time:
            </h2>
            <p class="sub-title text--white">
                <?=$order->delivery_duration;?>
            </p>




        <div class="card__table">
            <p class="on-way__text sub-text text--red text--small text-center">
                Confirmed Location:
            </p>
            <p class="sub-text text--blue text--small text-center">
                <?=$order->address;?>
            </p>
            <hr>
            <p class="on-way__text sub-text text--red text--small text-center">
                Confirmed Order:
            </p>
            <div class="card__wrap">
                <?php
                foreach ($order->orderItems as $item):
                    //var_dump($item->productItem);
                    ?>

                    <div class="card__item">
                        <div class="card__item--left">
                            <p class="text--small text--blue-opacity">
                                <strong>
                                   Flower <?=$item->item_price;?> | <span class="text--blue">$<?= $item->item_price; ?></span>
                                </strong>
                            </p>
                            <p class="card__text text--blue-opacity">
                                <?= $item->description; ?>
                            </p>
                            <p class="card__text text--blue-opacity">
                                <strong>
                                    Quantity: <span><?= $item->count; ?></span>
                                </strong>
                            </p>
                        </div>
                        <div class="card__item--right">
                            <p class="text--small text--blue">
                                <strong>
                                    $<?= number_format($item->total_price, 2); ?>
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
                <?php if ($order->status == \common\models\Order::ORDER_STATUS_IN_PROGRESS ||
                    $order->status == \common\models\Order::ORDER_STATUS_DELIVER_NEAR_PLACE ||
                    $order->status == \common\models\Order::ORDER_STATUS_DELIVER_AT_PLACE):?>
                <div class="card__item card__item--cancel">
                    <a href="<?=Url::toRoute(['site/cancel-order','l' => $order->weblink]);?>" class="card__text text--blue">
                        <strong>
                            Cancel Order
                        </strong>
                    </a>
                </div>
                <?php endif;?>

            </div>


                <blockquotes class="blockquotes text-left">
                    <p class="text--small">
                        <strong>Note:</strong> Cannabis products may vary slightly between delvery services
                    </p>
                </blockquotes>
                <p class="text--small text--blue">
                    <strong>Along with your cannabis gift
                        you’ll recieve:</strong>
                </p>
            <?= Html::img($order->supplier->getImageUrl(), ['class' => 'on-way__img']); ?>
                <p class="text--small text--blue-opacity">
                    <strong>1 <?=$order->supplier->product_name?></strong>
                </p>

            <p class="on-way__text--small text--blue-opacity text-left">
                <strong>Why am I receiving this:</strong>
                 DC Cannabis Law: Possession, purchase, and transportation of up to two ounces of marijuana for personal use by adults 21 and older. Transfer of up to one ounce of marijuana by adults 21 or older to another adult 21 or older. All transfers are to be free from remuneration; sales are still prohibited.
                <a href="https://www.mpp.org/states/district-of-columbia/summary-of-d-c-s-initiative-71">Learn more about the DC law, Initiative 71</a>
            </p>

        </div>
    </div>
</div>
<div class="deliver__info">
    <div class="container">
        <div class="flex-center flex-center--between">
            <div class="flex-center">
                <div class="deliver__info--logo">
                    <?= Html::img(Yii::$app->params['webUploadsDir'].$order->supplier->logo, ['class' => '']); ?>
                </div>
                <div class="deliver__info--content">
                    <p class="text text--white text--small"><strong><?=$order->deliver_name;?></strong> is on his way!</p>
                    <p class="text text--white text--small"><strong>Company</strong>: <?=$order->supplier->name;?></p>
                </div>
            </div>
            <div class="deliver__info--icons flex-center">
                <a href="tel:<?=preg_replace( '/[^0-9]/', '', \common\models\User::findById($order->supplier->supplier_id)->phone_number );?>" class="deliver__call"></a>
                <a href="sms:<?=preg_replace( '/[^0-9]/', '',\common\models\User::findById($order->supplier->supplier_id)->phone_number);?>" class="deliver__sms"></a>
            </div>
        </div>
    </div>
</div>

<?php if ($order->status == \common\models\Order::ORDER_STATUS_CANCELLED_BY_CUSTOMER):?>
<div class="modal modal--full-screen" id="cancel_order_by_customer" style="display: block;">
    <div class="modal__wrapper">
        <div class="modal__container container">
            <div class="modal__close"></div>
            <div class="modal__header">
                <br>
                <p class="text text--blue">Please call the delivery service to cancel your order</p>
                <br>
                <a href="tel:<?=preg_replace( '/[^0-9]/', '', \common\models\User::findById($order->supplier->supplier_id)->phone_number );?>">
                    <?= \common\models\User::findById($order->supplier->supplier_id)->phone_number ;?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<?php if ($order->status == \common\models\Order::ORDER_STATUS_CANCELLED_BY_DELIVER || $order->status == \common\models\Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER):?>
    <div class="modal modal--full-screen" id="cancel_order_by_supplier" style="display:block;">
        <div class="modal__wrapper">
            <div class="modal__container container">
                <div class="modal__close"></div>
                <div class="modal__header">
                    <br>
                    <p class="text text--blue">Sorry, supplier canceled an order</p>
                    <br>
                    <p class="text text--blue">You can call to the supplier</p>
                    <br>
                    <a href="tel:<?=preg_replace( '/[^0-9]/', '', \common\models\User::findById($order->supplier->supplier_id)->phone_number );?>">
                        <?= \common\models\User::findById($order->supplier->supplier_id)->phone_number ;?>
                    </a>
                    <br>
                    <p class="text text--blue">Or make an other order</p>
                    <br>
                    <a href="<?=Url::toRoute(['site/order']);?>" class="main-btn">
                        Catalog
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>
