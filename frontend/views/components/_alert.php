<?php

use yii\helpers\Html;

switch ($module):
    case "available":
        ?>
        <div class="alert alert--black">
            <div class="container flex-center">
                <?= Html::img('@web/img/icon_alarm.svg', ['class' => 'alert__icon']); ?>
                <p class="text text--small">
                    <strong class="text--white">Swift Delivery</strong> is currently only available in <strong
                            class="text--white">Washington DC.</strong>
                </p>
            </div>
        </div>
        <?php break;
    case "thank":
        ?>
        <div class="alert alert--black">
            <div class="container flex-center">
                <?= Html::img('@web/img/icon_glasses_emoji.png', ['class' => 'alert__icon']); ?>
                <div>
                    <p class="text text--small">
                        Thank for using <strong class="text--white">Swift Delivery!</strong> Hope to see you again soon.
                    </p>
                </div>
            </div>
        </div>
        <?php break;
        case "canceled":?>
            <div class="alert alert--black">
                <div class="container flex-center">
                    <div>
                        <p class="text text--small text--white">
                           Sorry, no one of the suppliers accepted your order
                        </p>
                        <p class="text text--small text--white">
                            Please, try to place an order a little later
                        </p>
                    </div>
                </div>
            </div>
            <?php break;?>
    <?php endswitch; ?>