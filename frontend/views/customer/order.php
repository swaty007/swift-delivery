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


<section class="order text-center">
    <div class="container">
        <h2 class="sub-title text--blue">
            Let’s Roll!
        </h2>
        <p class="sub-text text--green">
            Select your cannabis gift:
        </p>
        <div class="item__wrap">
            <?php foreach (Yii::$app->params['giftItems'] as $item): ?>
                <div class="item">
                    <?= Html::img($item['img'], ['class' => 'item__img']); ?>
                    <div class="item__content">
                        <h3 class="item__title text--small text--blue"><?= $item['name']; ?></h3>
                        <p class="item__desc text--blue-opacity">
                            starting at <strong
                                    class="text--green text--normal">$<?= number_format($item['price'], 2); ?></strong>
                        </p>
                    </div>
                    <a href="#" class="main-btn main-btn--sm w-100"
                       data-value="<?= $item['value']; ?>"
                       data-price="<?= number_format($item['price'], 2); ?>">
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
        <?php if (false): ?>
            <?php $form = ActiveForm::begin(['id' => 'form-create-order', 'class' => 'order__form', 'enableAjaxValidation' => true]);//, 'enableAjaxValidation' => true ?>
            <p class="sub-text text--green text--small">
                Basic information:
            </p>
            <hr>
            <?= $form->field($model, 'name')->textInput()->label('First Name:'); ?>
            <?= $form->field($model, 'name')->textInput()->label('Phone Number:'); ?>

            <p class="sub-text text--green text--small">
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
            <?= $form->field($model, 'textarea')->textarea(['placeholder' => 'Dont ring door bell, etc.'])->label("Any additional about your location:"); ?>

            <div class="form-group">
                <?= Html::submitButton('Submit Application', ['class' => 'main-btn']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>

    </div>
</section>
<section class="card">
    <div class="container">
        <p class="sub-text text--green text-center">
            What’s in your cart
        </p>
        <div class="card__wrap">
            <div class="card__item--empty">
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
                        $75.00
                    </p>
                    <a href="#" class="card__delete">

                    </a>
                </div>
            </div>
        </div>
        <div class="flex-center">
            <p class="text text--blue">
                Find a delivery service near you.
            </p>
            <button class="main-btn">Start delivery request</button>
        </div>
    </div>
</section>

<div class="modal">
    <div class="modal__wrapper">
        <div class="modal__container">
            <div class="modal__close"></div>
            <div class="modal__header">
                <img class="modal__img" src="/img/icon_flower.svg" alt="">
                <h3 class="modal__title sub-text">
                    Flower
                </h3>
            </div>
            <div class="modal__body">
                <select class="default-select" name="" id="">
                    <option value="">7 grams (1/4oz) sativa</option>
                    <option value="">7 grams (1/4oz) sativa</option>
                    <option value="">7 grams (1/4oz) sativa</option>
                </select>
                <div class="spinner">
                    <input id="item_quanitity" data-price="75" value="1"/>
                    <label class="sub-text text--green" for="item_quanitity">$75.00</label>
                </div>
            </div>
        </div>
    </div>
</div>




