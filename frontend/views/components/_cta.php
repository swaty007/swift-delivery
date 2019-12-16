<?php use yii\helpers\Html;
use yii\helpers\Url;

switch ($module):
    case "signUp":
        ?>
        <section class="cta cta--blue text-center">
            <div class="container">
                <h3 class="cta__title">
                    Delivery Companies
                </h3>
                <p class="text text--small">Find Customers Fast!</p>
                <a href="<?=Url::toRoute(['site/signup']);?>" class="main-btn main-btn--transparent">
                <span class="text--blue">
                    Sign Up Today
                </span>
                </a>
            </div>
        </section>
        <?php break;
    case "help-green":
        ?>
        <section class="cta cta--green text-center">
            <div class="container">
                <h3 class="cta__title">
                    Still Have Questions?
                </h3>
                <p class="text text--small">Visit our help center to learn about the full process and high quality of our service.</p>
                <a href="#" class="main-btn main-btn--transparent">
                <span class="text--green">
                    Help Center
                </span>
                </a>
            </div>
        </section>
        <?php break;
    case "help-blue":
        ?>
        <section class="cta cta--blue text-center">
            <div class="container">
                <h3 class="cta__title">
                    In the meantime
                </h3>
                <p class="text text--small">Visit our
                    <a href="<?=Url::toRoute(['site/index']);?>" class="text--white">help center</a> to learn about the full process and high quality of our service.
                </p>
                <a href="#" class="main-btn main-btn--transparent">
                <span class="text--blue">
                    Help Center
                </span>
                </a>
            </div>
        </section>
        <?php break;
    case "delivery":
        ?>
        <section class="cta cta--lg">
            <?= Html::img('@web/img/blue_bg.png', ['class' => 'cta__img--bg']); ?>
            <div class="container">
                <div class="flex-center">
                    <?= Html::img('@web/img/icon_time_delivery.svg', ['class' => 'cta__img']); ?>
                    <div>
                        <h3 class="cta__title cta__title--big">
                            21 minutes
                        </h3>
                        <p class="text">until cannabis hits your doorstep</p>
                    </div>
                </div>
                <a href="#" class="main-btn main-btn--lg main-btn--transparent">
                            <span class="text--blue">
                                Start Delivery!
                            </span>
                </a>
            </div>
        </section>
        <?php break; ?>
    <?php endswitch; ?>


