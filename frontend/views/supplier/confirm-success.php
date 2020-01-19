<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Congratulations!';
?>


<section class="confirm-supplier">
    <div class="confirm-supplier__container text-center">
        <div class="container">
            <h1 class="title text--white confirm-supplier__title">
                Congratulations!
            </h1>
            <p class="text confirm-supplier__text">
                Your application successfully received<br>
                Your admin will contact you and activate the account
            </p>
            <?= Html::img('@web/img/icon_congratulation.svg', ['class' => 'confirm-supplier__img']); ?>
        </div>
    </div>
    <?= $this->render('../components/_cta', ['module' => 'help-blue']); ?>
</section>

