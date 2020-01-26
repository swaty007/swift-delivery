<?php

use yii\helpers\Html;

?>

<div class="bg--green on-way">
    <div class="confirm-supplier__container text-center">
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
            <p class="on-way__text sub-text text--green text--small text-center">
                Confirmed Location:
            </p>
            <p class="sub-text text--blue text--small text-center">
                <?=$order->address;?>
            </p>
            <hr>
            <p class="on-way__text sub-text text--green text--small text-center">
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
                                   Flower <?=$item->item_price;?> | <span class="text--green">$<?= $item->item_price; ?></span>
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
                            <p class="text--small text--green">
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
                        <p class="text--small text--green">
                            <strong>
                                $<?= number_format($order->total, 2); ?>
                            </strong>
                        </p>
                    </div>
                </div>

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
                <strong>Why am I recieving this?</strong>  Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Learn more here.
            </p>

        </div>
    </div>
</div>