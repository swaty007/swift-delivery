<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model frontend\models\SupplierForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Order Form';
?>
<?= $this->render('../components/_alert', ['module' => 'available']); ?>


<section class="order">
    <div class="container">
        <h2 class="sub-title text--blue text-center">
            Let’s Roll!
        </h2>
        <p class="sub-text text--green text-center m35">
            Select your cannabis gift:
        </p>
        <div class="item__wrap">
            <?php foreach ($gifts as $item): ?>
                <div class="item">
                    <?= Html::img($item['image'], ['class' => 'item__img']); ?>
                    <div class="item__content">
                        <h3 class="item__title text--small text--blue"><?= $item['name']; ?></h3>
                        <p class="item__desc text--blue-opacity">
                            starting at <strong
                                    class="text--green text--normal">$<?= number_format($item['display_price'], 2); ?></strong>
                        </p>
                    </div>
                    <?php foreach ($item['productOptions'] as $option): ?>
                        <input type="hidden" class="item__data"
                               data-id="<?=$option['id'];?>"
                               data-product_id="<?= $option['product_id']; ?>"
                               data-name="<?= $option['name']; ?>"
                               data-price="<?= $option['price']; ?>"
                               data-order="<?= $option['order']; ?>"
                               data-is_active="<?= $option['is_active']; ?>">
                    <?php endforeach; ?>
                    <a href="#" class="main-btn main-btn--sm w-100 item__button">
                        Select
                    </a>
                </div>
            <?php endforeach; ?>

            <div class="item item--green item--center">
                <p class="text text--white">
                    <strong>
                        Coming Soon
                    </strong>
                </p>
            </div>

        </div>

        <?php $form = ActiveForm::begin(['id' => 'form-create-order', 'class' => 'order__form', 'enableAjaxValidation' => true]);//, 'enableAjaxValidation' => true ?>
        <p class="sub-text text--green text--small text-center">
            Basic information:
        </p>
        <hr>
        <?= $form->field($model, 'name')->textInput()->label('First Name:'); ?>
        <?= $form->field($model, 'phone_number')->textInput()->label('Phone Number:'); ?>

        <p class="sub-text text--green text--small text-center">
            What’s your location:
        </p>
        <hr>
        <div class="row flex-center">
            <div class="col-xs-8">
                <?= $form->field($model, 'zip', ['enableAjaxValidation' => true])->textInput(['id' => 'zip_validate'])->label('Zip Code:'); ?>
            </div>
            <div class="col-xs-4">
                <p id="zip_validate_status" class="supplier__text">
                    Enter zip code to
                    check availability
                </p>
            </div>
        </div>

        <?= $form->field($model, 'address')->textInput()->label('Address:'); ?>
        <?= $form->field($model, 'address_2')->textInput(['placeholder' => 'Addres Line 2 (optional)'])->label(false); ?>
        <?= $form->field($model, 'description')->textarea(['placeholder' => 'Dont ring door bell, etc.'])->label("Any additional about your location:"); ?>


        <p class="sub-text text--green text-center">
            What’s in your cart
        </p>
        <div class="card__wrap">
            <div class="card__item card__item--empty">
                <p class="text text--blue">
                    There’s nothing in your cart yet.
                </p>
            </div>
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
                    <a href="#" class="card__delete">

                    </a>
                </div>
            </div>
        </div>
        <div class="flex-center flex-center--between">
            <p class="text text--blue">
                <strong>
                    Find a delivery service near you.
                </strong>
            </p>
            <?= Html::submitButton('Start delivery request', ['class' => 'main-btn']) ?>
        </div>


        <?php ActiveForm::end(); ?>


    </div>
</section>
<section class="card">
    <div class="container">

    </div>
</section>

<div class="modal" id="add_to_card">
    <div class="modal__wrapper">
        <div class="modal__container container">
            <div class="modal__close"></div>
            <div class="modal__header">
                <img class="modal__img" src="/img/icon_flower.svg" alt="">
                <h3 class="modal__title sub-text">
                    Flower
                </h3>
            </div>
            <div class="modal__body">
                <select class="default-select" name="" id="modal_card_select">
                    <option value="">7 grams (1/4oz) sativa</option>
                    <option value="">7 grams (1/4oz) sativa</option>
                    <option value="">7 grams (1/4oz) sativa</option>
                </select>
                <p class="text--small text--blue-opacity">
                    Quanitity:
                </p>
                <div class="spinner">
                    <input id="item_quanitity" data-price="75" value="1"/>
                    <label class="sub-text text--green" for="item_quanitity">$75.00</label>
                </div>
                <blockquotes class="blockquotes">
                    <p class="text--small">
                        <strong>Note:</strong> product types may vary slightly between delvery services
                    </p>
                </blockquotes>
                <button id="add_to_card_btn" class="main-btn w100">Add to cart.</button>
            </div>
        </div>
    </div>
</div>




