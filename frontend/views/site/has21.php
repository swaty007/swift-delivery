<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Are you over 21 years of age?';
?>


<section class="confirm-supplier">
    <div class="confirm-supplier__container text-center">
        <div class="container">
            <h1 class="title text--white confirm-supplier__title">
                Are you over 21 years of age?
            </h1>
            <br>
            <br>
            <br>
            <div class="flex-center">
                <a href="<?=Url::toRoute(['site/has21','l'=> 'yes']);?>" class="main-btn">
                    Yes
                </a>
                <a href="<?=Url::toRoute(['site/has21','l'=> 'no']);?>" class="main-btn main-btn--blue">
                    No
                </a>
            </div>
        </div>
    </div>
</section>
