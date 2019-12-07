<?php

use yii\helpers\Html;

switch ($module):
    case "available":
        ?>
        <div class="alert alert--blue">
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
        <div class="alert alert--green">
            <div class="container flex-center">
                <?= Html::img('@web/img/icon_glasses_emoji.png', ['class' => 'alert__icon']); ?>
                <div>
                    <p class="text text--small">
                        Thank for using <strong class="text--white">Swift Delivery!</strong> Hope to see you again soon.
                    </p>
                    <a class="text text--small text--white" href="#">Share your love on social!</a>
                </div>
            </div>
        </div>
        <?php break; ?>
    <?php endswitch; ?>