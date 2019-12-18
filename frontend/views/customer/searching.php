<?php

use yii\helpers\Html;

?>


<section class="bg--blue searching">
    <div class="confirm-supplier__container confirm-supplier__container--blue text-center">
        <div class="container">
            <h1 class="sub-title text--white confirm-supplier__sub-title">
                Searching Delivery<br>
                Companies Nearby...
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
            <p class="searching__text sub-text text--green text--small text-center">Confirmed Location:</p>
            <p class="sub-text text--blue text--small text-center">1234 Hiccup St.<br>
                Washington DC. 20005</p>
            <hr>
            <p class="searching__text sub-text text--green text--small text-center">Confirmed Order:</p>
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
            </div>
        </div>
    </div>
</section>
