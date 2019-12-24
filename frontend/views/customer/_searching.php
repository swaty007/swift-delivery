<?php

use yii\helpers\Html;

?>

<div class="bg--blue searching">
    <div class="confirm-supplier__container--blue confirm-supplier__container text-center">
        <div class="container">
            <h1 class="text--white sub-title confirm-supplier__sub-title">
                <?= $order->getStatusText(); ?>
            </h1>

            <p class="text confirm-supplier__text">
                This may take several minutes. We will send you a text when your order is confirmed.
            </p>
            <?= Html::img('@web/img/spinner.svg', ['class' => 'confirm-supplier__img searching__img']); ?>
            <div class="searching__loader"></div>
            <div id="circularG">
                <div id="circularG_1" class="circularG"></div>
                <div id="circularG_2" class="circularG"></div>
                <div id="circularG_3" class="circularG"></div>
                <div id="circularG_4" class="circularG"></div>
                <div id="circularG_5" class="circularG"></div>
                <div id="circularG_6" class="circularG"></div>
                <div id="circularG_7" class="circularG"></div>
                <div id="circularG_8" class="circularG"></div>
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
            <p class="searching__text sub-text text--green text--small text-center">
                Confirmed Location:
            </p>
            <p class="sub-text text--blue text--small text-center">
                <?= $order->address; ?>
            </p>
            <hr>
            <p class="searching__text sub-text text--green text--small text-center">
                Confirmed Order:
            </p>
            <div class="card__wrap">
                <?php
                $totalOrder = 0;
                foreach ($order->orderItems as $item):
                    $totalOrder += $item->total_price;
                    //var_dump($item->productItem);
                    ?>

                    <div class="card__item">
                        <div class="card__item--left">
                            <p class="text--small text--blue-opacity">
                                <strong>
                                    Flower<?= $item->item_price; ?> | <span
                                            class="text--green">$<?= $item->item_price; ?></span>
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
                        <p class="text text--blue-opacity">
                            <strong>Order Total</strong>
                        </p>
                        <p class="text--small text--blue-opacity">Cash upon delivery</p>
                    </div>
                    <div class="card__item--right">
                        <p class="text text--green">
                            <strong>
                                $<?= number_format($totalOrder, 2); ?>
                            </strong>
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>