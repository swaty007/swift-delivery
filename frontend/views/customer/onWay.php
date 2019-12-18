<?php

use yii\helpers\Html;

?>


<section class="bg--green on-way">
    <div class="confirm-supplier__container text-center">
        <div class="container">
            <h1 class="title text--white confirm-supplier__title">
                Congratulations!
            </h1>
            <p class="text confirm-supplier__text">
                Your order is on its way!
            </p>
            <?= Html::img('@web/img/icon_congratulation.svg', ['class' => 'confirm-supplier__img']); ?>
        </div>
    </div>
    <div class="container text-center">
        <h2 class="on-way__title text--white-opacity">
            Estimated delivery time:
        </h2>
        <p class="sub-title text--white">
            5:47pm | 15 minutes
        </p>

        <div class="card__table">
            <p class="on-way__text sub-text text--green text--small text-center">Confirmed Location:</p>
            <p class="sub-text text--blue text--small text-center">1234 Hiccup St.<br>
                Washington DC. 20005</p>
            <hr>
            <p class="on-way__text sub-text text--green text--small text-center">Confirmed Order:</p>
            <div class="card__wrap">

                <div class="card__item">
                    <div class="card__item--left">
                        <p class="text--small text--blue-opacity">
                            <strong>
                                Flower | <span class="text--green">$200</span>
                            </strong>
                        </p>
                        <p class="card__text text--blue-opacity">
                            7 grams (1/4oz) sativa
                        </p>
                        <p class="card__text text--blue-opacity">
                            <strong>
                                Quantity: <span>1</span>
                            </strong>
                        </p>
                    </div>
                    <div class="card__item--right">
                        <p class="text--small text--green">
                            <strong>
                                $75.00
                            </strong>
                        </p>
                    </div>
                </div>

                <div class="card__item card__item--total">
                    <div class="card__item--left">
                        <p class="text--small text--blue-opacity">
                            <strong>Total</strong>
                        </p>

                    </div>
                    <div class="card__item--right">
                        <p class="text--small text--green">
                            <strong>
                                $75.00
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

        </div>

    </div>
</section>
