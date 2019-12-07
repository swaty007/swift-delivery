<?php use yii\helpers\Html; ?>
<section class="how-it-works text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="sub-title text--blue">
                    How it works:
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="how-it-works__item">
                    <div class="how-it-works__item-circle">
                        01
                    </div>
                    <p class="how-it-works__item-text text--blue">
                        Start your order by completing the
                        <a class="text--green" href="#">Minimal Order Form</a>.
                    </p>
                    <div class="how-it-works__item-img">
                        <?= Html::img('@web/img/hww_icon_1.svg', ['class' => '']); ?>
                    </div>
                </div>
                <div class="how-it-works__item">
                    <div class="how-it-works__item-circle">
                        02
                    </div>
                    <p class="how-it-works__item-text text--blue">
                        Enter your first name, location, and select your cannabis gift.
                    </p>
                    <div class="how-it-works__item-img">
                        <?= Html::img('@web/img/hww_icon_2.svg', ['class' => '']); ?>
                    </div>
                </div>
                <div class="how-it-works__item">
                    <div class="how-it-works__item-circle">
                        03
                    </div>
                    <p class="how-it-works__item-text text--blue">
                        Pay cash only, and recieve your cannabis gift in record time from the nearest delivery distributor!
                    </p>
                    <div class="how-it-works__item-img">
                        <?= Html::img('@web/img/hww_icon_3.svg', ['class' => '']); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="#" class="main-btn">
                    Start Delivery!
                </a>
            </div>
        </div>
    </div>
</section>