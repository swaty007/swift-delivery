<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="bg--black searching">
    <div class="confirm-supplier__container--black confirm-supplier__container text-center">
        <div class="container">
            <h1 class="text--white sub-title confirm-supplier__sub-title">
                <?= $order->getStatusText(); ?>
            </h1>

            <p class="text confirm-supplier__text">
                This may take several minutes. We will send you a text when your order is confirmed.
            </p>

           <div class="row">
               <div class="col-md-12">
                   <div class="searching__loader"></div>
               </div>
           </div>

        </div>
    </div>
    <div class="container text-center">

        <h2 class="searching__title text--white">
            Don’t close this window
        </h2>
        <p class="text text--small">
            Please wait here until you are redirected to the “Completed Order” Screen.
        </p>

        <div class="card__table">
            <p class="searching__text sub-text text--red text--small text-center">
                Confirmed Location:
            </p>
            <p class="sub-text text--blue text--small text-center">
                <?= $order->address; ?>
            </p>
            <hr>
            <p class="searching__text sub-text text--red text--small text-center">
                Confirmed Order:
            </p>
            <div class="card__wrap">
                <?php
                foreach ($order->orderItems as $item):
//                    var_dump($order->orderItems);
//                    var_dump($item);
                    ?>

                    <div class="card__item">
                        <div class="card__item--left">
                            <p class="text--small text--blue-opacity">
                                <strong>
                                    <?= \common\models\Product::findOne(['id' => $item->product_item_id])->name ?> | <span
                                            class="text--blue">$<?= $item->item_price; ?></span>
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
                        <p class="text text--blue-opacity">
                            <strong>Order Total</strong>
                        </p>
                        <p class="text--small text--blue-opacity">Cash upon delivery</p>
                    </div>
                    <div class="card__item--right">
                        <p class="text text--red">
                            <strong>
                                $<?= number_format($order->total, 2); ?>
                            </strong>
                        </p>
                    </div>
                </div>

                <div class="card__item card__item--cancel">
                    <a href="<?=Url::toRoute(['site/order-status','cancelCustomer' => $order->weblink]);?>" class="card__text text--blue">
                        <strong>
                            Cancel Order
                        </strong>
                    </a>
                </div>


            </div>

        </div>
    </div>
</div>