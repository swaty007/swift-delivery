<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="order-complete">

    <div class="container text-center">
        <h1 class="text--green sub-title">
            <?= $order->getStatusText(); ?>
        </h1>
        <hr>
        <p class="text text--blue-opacity order-complete__sub-title">
            How was <?=$order->deliver_name?>, your delivery person?
        </p>
        <?php $form = ActiveForm::begin(['id' => 'form-rate', 'enableAjaxValidation' => true, 'options' => ['class' => 'order__form']]); ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="order-complete__form-group">
                    <?= $form->field($model, 'friendly', ['options' => ['class' => 'default-checkbox__container default-checkbox__container--label']])
                        ->checkbox([
                            'uncheck' => null,
                            'class' => 'default-checkbox',
                            'template' => "<div class='checkbox'>{input}{label}</div>"])
                        ->label('Friendly')->error(false); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="order-complete__form-group">
                    <?= $form->field($model, 'fulfilled', ['options' => ['class' => 'default-checkbox__container default-checkbox__container--label']])
                        ->checkbox([
                            'uncheck' => null,
                            'class' => 'default-checkbox',
                            'template' => "<div class='checkbox'>{input}{label}</div>"])
                        ->label('Fulfilled order')->error(false); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="order-complete__form-group">
                    <?= $form->field($model, 'onTime', ['options' => ['class' => 'default-checkbox__container default-checkbox__container--label']])
                        ->checkbox([
                            'uncheck' => null,
                            'class' => 'default-checkbox',
                            'template' => "<div class='checkbox'>{input}{label}</div>"])
                        ->label('On time')->error(false); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="order-complete__form-group">
                    <?= $form->field($model, 'again', ['options' => ['class' => 'default-checkbox__container default-checkbox__container--label']])
                        ->checkbox([
                            'uncheck' => null,
                            'class' => 'default-checkbox',
                            'template' => "<div class='checkbox'>{input}{label}</div>"])
                        ->label('Would use again')->error(false); ?>
                </div>
            </div>
        </div>
        <p class="text text--blue-opacity order-complete__sub-text">
            Rate your experience.
        </p>
        <?= $form->field($model, 'stars')->hiddenInput(['id' => 'stars_form']); ?>
        <div class="stars" id="start_select">
            <div class="stars__block"></div>
            <div class="stars__block"></div>
            <div class="stars__block"></div>
            <div class="stars__block"></div>
            <div class="stars__block"></div>
        </div>
        <?= $form->field($model, 'comment', ['options' => ['class' => 'text-center form-group']])->textarea(['placeholder' => ''])->label("Additional Comments:"); ?>
    </div>

        <?= Html::submitButton('Send Review', ['class' => 'main-btn']) ?>
        <?php ActiveForm::end(); ?>
</div>
