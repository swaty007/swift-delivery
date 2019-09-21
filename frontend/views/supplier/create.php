<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create supplier';
?>
<section class="supplier">
    <div class="container">
        <h1 class="sub-title text--blue">Delivery Service<br>
            Application</h1>
        <p class="text text--small">Name & Location</p>
        <hr class="line">

        <?php $form = ActiveForm::begin(['id' => 'form-create-supplier', 'class' => 'access__form']); ?>
        <!--        --><? //= $form->field($model, 'password')->label(false)->passwordInput() ?>
        <!--        --><? //= $form->field($model, 'password_repeat')->label(false)->passwordInput() ?>

        <?= $form->field($model, 'name')->textInput()->label('Company Name:'); ?>
        <?= $form->field($model, 'zip')->textInput()->label('Zip Code:'); ?>
        <?= $form->field($model, 'address')->textInput()->label('Address:'); ?>
        <?= $form->field($model, 'address_2')->textInput(['placeholder' => 'Addres Line 2 (optional)'])->label(false); ?>
        <?= $form->field($model, 'web_url')->textInput()->label('Website URL:'); ?>

        <div class="fileContainer">
            <img class="fileContainer__img" src=""/>
            <p class="fileContainer__text--select text--blue-opacity text--small">Upload new image
                here <?= Html::img('@web/img/icon_upload.svg', ['class' => 'fileContainer__img--icon']); ?></p>
            <!--            <input type="file" name="file" >-->
            <?= $form->field($model, 'logo')->fileInput()->label(false) ?>
        </div>
        <h3 class="sub-text text--green text--small">
            What cannabis products do you sell?
        </h3>
        <div class="item__wrap">
            <div class="item">
                <?= Html::img('@web/img/icon_flower.svg', ['class' => 'item__img']); ?>
                <div class="item__content">
                    <h3 class="item__title text--small text--blue">Flower</h3>
                    <p class="item__desc text--blue-opacity"></p>
                </div>
                <!--                <label class="">-->
                <!--                    <input type="checkbox" class="default-checkbox">-->
                <?= $form->field($model, 'product_name', ['options' => ['class' => 'default-checkbox__container']])
                    ->checkbox(['label' => 'Option4', 'value' => 3, 'uncheck' => null, 'class' => 'default-checkbox'])
                    ->label(false); ?>
                <!--                    <span class="default-checkbox__mark"></span>-->
                <!--                </label>-->
            </div>
        </div>

        <p class="text text--small text--blue-opacity">
            Because cannabis has to be gifted, please upload an image of and provide a name for the product customers
            are paying for.
        </p>
        <?= $form->field($model, 'product_name')->textInput()->label('Company Name:'); ?>
        <div class="fileContainer">
            <img class="fileContainer__img" src=""/>
            <p class="fileContainer__text--select text--blue-opacity text--small">Upload new image
                here <?= Html::img('@web/img/icon_upload.svg', ['class' => 'fileContainer__img--icon']); ?></p>
            <!--            <input type="file" name="file" >-->
            <?= $form->field($model, 'product_image')->fileInput()->label(false) ?>
        </div>

        <h3 class="sub-text text--green text--small">
            Select your monthly price plan
        </h3>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</section>
