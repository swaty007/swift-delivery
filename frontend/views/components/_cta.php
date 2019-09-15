<?php use yii\helpers\Html;

switch ($cta):
    case "signUp":
        ?>
<section class="cta cta--blue text-center">
    <div class="container">
            <h3 class="cta__title">
                Delivery Companies
            </h3>
            <p class="text text--small">Find Customers Fast!</p>
            <a href="#" class="main-btn main-btn--transparent">
                <span class="text--blue">
                    Sign Up Today
                </span>
            </a>
        <?php break;
    case "help":
        ?>
<section class="cta cta--green text-center">
    <div class="container">
            <h3 class="cta__title">
                Still Have Questions?
            </h3>
            <p class="text text--small">Vist our help center to learn about the full process and high quality of our service.</p>
            <a href="#" class="main-btn main-btn--transparent">
                <span class="text--green">
                    Help Center
                </span>
            </a>
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
                                <?php break; ?>
    <?php endswitch; ?>
                    </div>
                </section>

