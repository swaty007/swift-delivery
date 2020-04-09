<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Supplier cabinet';
?>
<section class="supplier-cab">
    <?php \yii\widgets\Pjax::begin(['id' => 'supplier_tables',
        'options' => [
            'class' => '',
            'tag' => 'div'
        ]]); ?>
    <hr class="full">
    <div class="container">
        <div class="supplier-cab__menu">
            <div class="supplier-cab__username">
<!--                <p class="text--large"><strong>Hi,</strong> --><?//=Yii::$app->user->identity->username?><!--!</p>-->
                <p class="text--large"><strong>Hi,</strong> <?=$this->params['supplierModel']->name?>!</p>
                <div class="stars stars--left">
                    <?php
                    for ($i = 1; $i <= 5; $i++):
                        if ($rating >= 1): ?>
                            <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                        <?php elseif($rating <= 0):?>
                            <?= Html::img('@web/img/icon_star_empty.svg', ['class' => '']); ?>
                        <?php elseif($rating == 0.5):?>
<!--                            icon_star_half-->
                        <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                        <?php elseif($rating > 0.5):?>
<!--                            icon_star_high-->
                        <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                        <?php elseif($rating < 0.5):?>
<!--                            icon_star_low-->
                        <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                        <?php endif;?>
                        <?php
                        $rating--;
                    endfor;?>
                </div>
            </div>
            <a href="<?=Url::toRoute('/supplier/edit-profile')?>" class="main-btn main-btn--black main-btn--settings">Settings</a>
        </div>
    </div>
    <hr class="full">
    <div class="container">
        <div class="supplier-cab__monthly">
            <div class="supplier-cab__monthly--left">
                <p class="supplier-cab__text text--blue-opacity">
                    Earnings for<br>
                    last 30 days:
                </p>
                <h4 class="supplier-cab__monthly--text text--green">
                    $<?= number_format($mounthlyEarnings) ?>
                </h4>
            </div>
            <div class="supplier-cab__line"></div>
            <div class="supplier-cab__monthly--right">
                <p class="supplier-cab__text text--blue-opacity">Accepted: <strong class="text--green"><?= $accepted ?></strong></p>
                <p class="supplier-cab__text text--blue-opacity">Earnings: <strong class="text--green"><?= $earnings; ?></strong></p>
            </div>
        </div>
    </div>
    <hr class="full">
    <div class="container">

        <h2 class="text--blue text text--bold">
            Orders In Progress
        </h2>
        <table class="supplier-cab__table">
            <tr>
                <th>Date:</th>
                <th>Total:</th>
                <th></th>
            </tr>
            <?php foreach ($inProgress as $item): ?>
                <tr>
                    <td>
                        <?= $item['created_at']; ?>
                    </td>
                    <td>
                        <?= $item['total']; ?>
                    </td>
                    <td>
                        <button class="btn-sm main-btn main-btn--black main-btn--xs" data-direction="show-more-orders">
                            Show More
                        </button>
                    </td>
                </tr>
                <tr class="supplier-cab__table--content">
                    <td colspan="3">
                        <div class="supplier-cab__table-content">

                            <h4 class="supplier-cab__table-content--title text--xs">
                                Company: <span class="text--regular"><?=$item['supplier']['name']?></span>
                            </h4>
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Order #: <a href="<?= Url::toRoute('/supplier/show-order?l=') . $item['weblink'] ?>">
                                    <span class="text--regular"><?= $item['id'] ?></span>
                                </a>
                            </h4>
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Delivering to:

                                <span class="text--regular">
                                <?= $item['address'] ?>
                                    <?php if ($item['address_2']): ?>
                                        <?= $item['address_2'] ?>
                                    <?php endif; ?>
                                / <?= $item['zip'] ?>
                            </span>
                            </h4>

                            <?php if(\common\models\AddressLatlng::tryGetAddressData($item['address'] . ' ' . $item['address_2'] . ' ' . $item['zip']) !== null):?>
                                <img class="supplier-cab__map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$item['address']?>&zoom=13&size=300x300&maptype=roadmap
&markers=color:green%7Clabel:D%7C<?=\common\models\AddressLatlng::tryGetAddressData($item['address'] . ' ' . $item['address_2'] . ' ' . $item['zip'])->latlng?>
&key=<?=Yii::$app->params['googleMapsApiKey']?>" alt="Map">
                            <?php endif;?>
                            <h4 class="text--xs">
                                Gift Order
                            </h4>
                            <div class="card__wrap">
                                <?php foreach ($item['orderItems'] as $key => $gift): ?>
                                    <div class="card__item">
                                        <div class="card__item--left">
                                            <p class="text--xs text--blue-opacity">
                                                <strong>
                                                    Flower | <span class="text--blue">$<?= $gift['item_price'] ?></span>
                                                </strong>
                                            </p>
                                            <p class="card__text text--blue-opacity">
                                                <?= $gift['description'] ?>
                                            </p>
                                            <p class="card__text text--blue-opacity">
                                                <strong>
                                                    Quantity: <span><?= $gift['count'] ?></span>
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="card__item--right">
                                            <p class="text--xs text--blue">
                                                <strong>
                                                    $<?= number_format($gift['total_price'], 2); ?>
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="card__item card__item--total">
                                    <div class="card__item--left">
                                        <p class="text--small text--blue-opacity">
                                            <strong>Total</strong>
                                        </p>
                                    </div>
                                    <div class="card__item--right">
                                        <p class="text--small text--red">
                                            <strong>
                                                $<?= number_format($item['total'], 2); ?>
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <p class="text--xs text--blue-opacity">
                                    <strong>&nbsp;</strong>
                                </p>
                                <p class="text--xs text--blue-opacity">
                                    Gift Product: <?=$item['supplier']['product_name']?>
                                </p>
                                <?= Html::img(Yii::$app->params['webUploadsDir'].$item['supplier']['product_image'], ['class' => 'on-way__img']); ?>
                            </div>

                            <!--                            <h4 class="supplier-cab__table-content--title text--xs">-->
                            <!--                                Delivery Status: <span class="text--regular">--><?//= \common\models\Order::getStatusTextFromStatus($item['status']) ?><!--</span>-->
                            <!--                            </h4>-->
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Zip Code: <span class="text--regular"><?= $item['zip'] ?></span>
                            </h4>
                            <div class="flex-center">
                                <a href="<?=Url::toRoute(['supplier/index','cancelSupplier' => $item['id']]);?>" class="btn-sm main-btn main-btn--xs main-btn--red">
                                    Cancel order
                                </a>
                                <a href="<?=Url::toRoute(['supplier/index','complete' => $item['id']]);?>" class="btn-sm main-btn main-btn--xs main-btn--black">
                                    Complete order
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2 class="text--blue text text--bold">
            Allowed Orders
        </h2>
        <table class="supplier-cab__table">
            <thead>
            <tr>
                <th>Date:</th>
                <th>Total:</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allowed as $item): ?>
                <tr>
                    <td>
                        <?= $item['created_at']; ?>
                    </td>
                    <td>
                        <?= $item['total']; ?>
                    </td>
                    <td>
                        <button class="btn-sm main-btn main-btn--black main-btn--xs" data-direction="show-more-orders">
                            Show More
                        </button>
                    </td>
                </tr>
            <tr class="supplier-cab__table--content">
                <td colspan="3">
                    <div class="supplier-cab__table-content">


                        <h4 class="supplier-cab__table-content--title text--xs">
                            Company: <span class="text--regular"><?=$item['supplier']['name']?></span>
                        </h4>
                        <h4 class="supplier-cab__table-content--title text--xs">
                            Order #: <a href="<?= Url::toRoute('/supplier/show-order?l=') . $item['weblink'] ?>" class="text--regular"><?= $item['weblink'] ?></a>
                        </h4>
                        <h4 class="supplier-cab__table-content--title text--xs">
                            Delivering to:
                            <span class="text--regular">
                                <?= $item['address'] ?>
                                <?php if ($item['address_2']): ?>
                                    <?= $item['address_2'] ?>
                                <?php endif; ?>
                                / <?= $item['zip'] ?>
                            </span>
                        </h4>

                        <?php if(\common\models\AddressLatlng::tryGetAddressData($item['address'] . ' ' . $item['address_2'] . ' ' . $item['zip']) !== null):?>
                            <img class="supplier-cab__map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$item['address']?>&zoom=13&size=300x300&maptype=roadmap
&markers=color:green%7Clabel:D%7C<?=\common\models\AddressLatlng::tryGetAddressData($item['address'] . ' ' . $item['address_2'] . ' ' . $item['zip'])->latlng?>
&key=<?=Yii::$app->params['googleMapsApiKey']?>" alt="Map">
                        <?php endif;?>
                        <h4 class="text--xs">
                            Gift Order
                        </h4>
                        <div class="card__wrap">
                            <?php foreach ($item['orderItems'] as $key => $gift): ?>
                            <div class="card__item">
                                <div class="card__item--left">

                                    <p class="text--xs text--blue-opacity">
                                        <strong>
                                            Flower | <span class="text--blue">$<?= $gift['item_price'] ?></span>
                                        </strong>
                                    </p>
                                    <p class="card__text text--blue-opacity">
                                        <?= $gift['description'] ?>
                                    </p>
                                    <p class="card__text text--blue-opacity">
                                        <strong>
                                            Quantity: <span><?= $gift['count'] ?></span>
                                        </strong>
                                    </p>
                                </div>
                                <div class="card__item--right">
                                    <p class="text--xs text--blue">
                                        <strong>
                                            $<?= number_format($gift['total_price'], 2); ?>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div class="card__item card__item--total">
                                <div class="card__item--left">
                                    <p class="text--small text--blue-opacity">
                                        <strong>Total</strong>
                                    </p>
                                </div>
                                <div class="card__item--right">
                                    <p class="text--small text--red">
                                        <strong>
                                            $<?= number_format($item['total'], 2); ?>
                                        </strong>
                                    </p>
                                </div>
                            </div>
<!--                            <p class="text--xs text--blue-opacity">-->
<!--                                <strong>&nbsp;</strong>-->
<!--                            </p>-->
<!--                            <p class="text--xs text--blue-opacity">-->
<!--                                Gift Product: --><?//=$item['supplier']['product_name']?>
<!--                            </p>-->
<!--                            --><?//= Html::img(Yii::$app->params['webUploadsDir'].$item['supplier']['product_image'], ['class' => 'on-way__img']); ?>
                        </div>

<!--                        <h4 class="supplier-cab__table-content--title text--xs">-->
<!--                            Delivery Status: <span class="text--regular">--><?//= \common\models\Order::getStatusTextFromStatus($item['status']) ?><!--</span>-->
<!--                        </h4>-->
                        <h4 class="supplier-cab__table-content--title text--xs">
                            Zip Code: <span class="text--regular"><?= $item['zip'] ?></span>
                        </h4>
                        <a href="#" class="btn-sm main-btn main-btn--xs main-btn--black" data-direction="take-order" data-order-id="<?= $item['id'] ?>">
                            Take
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


        <h2 class="text--blue text text--bold">
            Order History:
        </h2>
        <table class="supplier-cab__table">
            <tr>
                <th>Date:</th>
                <th>Total:</th>
                <th></th>
            </tr>
            <?php foreach ($finished as $item): ?>
                <tr>
                    <td>
                        <?= $item['created_at']; ?>
                    </td>
                    <td>
                        <?= $item['total']; ?>
                    </td>
                    <td>
                        <button class="btn-sm main-btn main-btn--black main-btn--xs" data-direction="show-more-orders">
                            Show more
                        </button>
                    </td>
                </tr>
                <tr class="supplier-cab__table--content">
                    <td colspan="3">
                        <div class="supplier-cab__table-content">
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Company: <span class="text--regular"><?=$item['supplier']['name']?></span>
                            </h4>
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Order #: <a href="<?= Url::toRoute('/supplier/show-order?l=') . $item['weblink'] ?>" class="text--regular"><?= $item['weblink'] ?></a>
                            </h4>
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Delivering to:
                                <span class="text--regular">
                                <?= $item['address'] ?>
                                    <?php if ($item['address_2']): ?>
                                        <?= $item['address_2'] ?>
                                    <?php endif; ?>
                                / <?= $item['zip'] ?>
                            </span>
                            </h4>

                            <?php if(\common\models\AddressLatlng::tryGetAddressData($item['address'] . ' ' . $item['address_2'] . ' ' . $item['zip']) !== null):?>
                                <img class="supplier-cab__map" src="https://maps.googleapis.com/maps/api/staticmap?center=<?=$item['address']?>&zoom=13&size=300x300&maptype=roadmap
&markers=color:green%7Clabel:D%7C<?=\common\models\AddressLatlng::tryGetAddressData($item['address'] . ' ' . $item['address_2'] . ' ' . $item['zip'])->latlng?>
&key=<?=Yii::$app->params['googleMapsApiKey']?>" alt="Map">
                            <?php endif;?>
                            <h4 class="text--xs">
                                Gift Order
                            </h4>
                            <div class="card__wrap">
                                <?php foreach ($item['orderItems'] as $key => $gift): ?>
                                    <div class="card__item">
                                        <div class="card__item--left">
                                            <p class="text--xs text--blue-opacity">
                                                <strong>
                                                    Flower | <span class="text--blue">$<?= $gift['item_price'] ?></span>
                                                </strong>
                                            </p>
                                            <p class="card__text text--blue-opacity">
                                                <?= $gift['description'] ?>
                                            </p>
                                            <p class="card__text text--blue-opacity">
                                                <strong>
                                                    Quantity: <span><?= $gift['count'] ?></span>
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="card__item--right">
                                            <p class="text--xs text--blue">
                                                <strong>
                                                    $<?= number_format($gift['total_price'], 2); ?>
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="card__item card__item--total">
                                    <div class="card__item--left">
                                        <p class="text--small text--blue-opacity">
                                            <strong>Total</strong>
                                        </p>
                                    </div>
                                    <div class="card__item--right">
                                        <p class="text--small text--red">
                                            <strong>
                                                $<?= number_format($item['total'], 2); ?>
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <p class="text--xs text--blue-opacity">
                                    <strong>&nbsp;</strong>
                                </p>
                                <p class="text--xs text--blue-opacity">
                                    Gift Product: <?=$item['supplier']['product_name']?>
                                </p>
                                <?= Html::img(Yii::$app->params['webUploadsDir'].$item['supplier']['product_image'], ['class' => 'on-way__img']); ?>
                            </div>

<!--                            <h4 class="supplier-cab__table-content--title text--xs">-->
<!--                                Delivery Status: <span class="text--regular">--><?//= \common\models\Order::getStatusTextFromStatus($item['status']) ?><!--</span>-->
<!--                            </h4>-->
                            <h4 class="supplier-cab__table-content--title text--xs">
                                Zip Code: <span class="text--regular"><?= $item['zip'] ?></span>
                            </h4>
                            <?php if ($item['rating']):?>
                                <h4 class="supplier-cab__table-content--title text--xs">
                                    Delivery Review:
                                </h4>
                                <?php if (
                                    $item['rating']['is_friendly'] ||
                                    $item['rating']['is_fulfilled'] ||
                                    $item['rating']['is_on_time'] ||
                                    $item['rating']['would_use_again'] ):?>
                                    <p class="supplier-cab__table-content--title text--xs text--regular">
                                        Q: How was your delivery person?
                                    </p>
                                    <p class="supplier-cab__table-content--title text--xs text--regular">
                                        A: <?=$item['rating']['is_friendly'] ? 'Friendly' : '';?>
                                        <?=$item['rating']['is_fulfilled'] ? 'Fufilled order' : '';?>
                                        <?=$item['rating']['is_on_time'] ? 'On time' : '';?>
                                        <?=$item['rating']['would_use_again'] ? 'Would use again' : '';?>
                                    </p>
                                <?php endif;?>


                                <p class="supplier-cab__table-content--title text--xs text--regular">
                                    Q: Rating?
                                </p>
                                <div class="supplier-cab__table-content--title text--xs text--regular">
                                    <div class="stars stars--left">
                                        A:&nbsp;
                                        <?php
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($item['rating']['rating'] >= 1): ?>
                                                <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                                            <?php elseif($item['rating']['rating'] <= 0):?>
                                                <?= Html::img('@web/img/icon_star_empty.svg', ['class' => '']); ?>
                                            <?php elseif($item['rating']['rating'] == 0.5):?>
                                                <!--                            icon_star_half-->
                                                <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                                            <?php elseif($item['rating']['rating'] > 0.5):?>
                                                <!--                            icon_star_high-->
                                                <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                                            <?php elseif($item['rating']['rating'] < 0.5):?>
                                                <!--                            icon_star_low-->
                                                <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                                            <?php endif;?>
                                            <?php
                                            $item['rating']['rating']--;
                                        endfor;?>
                                    </div>
                                </div>
                                <?php if ($item['rating']['comment']):?>
                                    <p class="supplier-cab__table-content--title text--xs text--regular">
                                        Q: Additional comments:
                                    </p>
                                    <p class="supplier-cab__table-content--title text--xs text--regular">
                                        A: <?=$item['rating']['comment'];?>
                                    </p>
                                <?php endif;?>
                            <?php endif;?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
    <?php if (!empty($inProgress)):?>
        <div class="deliver__info">
            <div class="container">
                <div class="flex-center flex-center--between">
                    <div class="deliver__info--content">
                        <p class="text text--white text--small"><strong><?=$inProgress[0]['customer']['username'];?></strong> is your customer!</p>
                        <p class="text">
                            <a href="<?=Url::toRoute(['supplier/index','cancelSupplier' => $inProgress[0]['id']]);?>" class="text text--red text--xs">
                                <?= Html::img('@web/img/icon_cancel.svg', ['class' => '']) ?>
                                Cancel Order
                            </a>
                        </p>
                    </div>
                    <div class="deliver__info--icons flex-center">
                        <a href="tel:<?=preg_replace( '/[^0-9]/', '', $inProgress[0]['customer']['phone_number'] );?>" class="deliver__call"></a>
                        <a href="sms:<?=preg_replace( '/[^0-9]/', '', $inProgress[0]['customer']['phone_number'] );?>" class="deliver__sms"></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>


    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <?php if (Yii::$app->session->getFlash('success') === 'ORDER_STATUS_CANCELLED_BY_SUPPLIER' ||
            Yii::$app->session->getFlash('success') === 'ORDER_STATUS_CANCELLED_BY_DELIVER'): ?>
            <div class="modal modal--full-screen" id="cancel_order_by_customer" style="display:block;"
            onclick="window.history.pushState({}, document.title, window.location.pathname )">
                <div class="modal__wrapper">
                    <div class="modal__container container">
                        <div class="modal__close"></div>
                        <div class="modal__header">
                            <br>
                            <p class="text text--blue">
                                Please call the customer to cancel the order.
                            </p>
                            <br>
                            <p class="text text--blue">
                                Tell them to go back to the homepage & start a new order
                            </p>
                            <br>
                            <a href="tel:<?=preg_replace( '/[^0-9]/', '', $finished[0]['customer']['phone_number'] );?>">
                                <?= $finished[0]['customer']['phone_number'] ;?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>

    <script>
        clearInterval(intervarPjax)
        var intervarPjax = setInterval(function () {
            if (typeof $.pjax !== 'undefined') {
                if (!$('.supplier-cab__table-content').is(':visible') && !$('.modal').is(':visible')) {
                  $.pjax.reload({container: "#supplier_tables"});
                }
            }
        }, 5000)
    </script>

    <?php \yii\widgets\Pjax::end(); ?>
</section>

<div class="modal modal--full-screen modal--order" id="take_order">
    <div class="modal__wrapper">
        <div class="modal__container container">
            <div class="modal__close"></div>
            <div class="modal__header">
                <input type="hidden" id="modal_take_order_id">
                <h3 class="modal__title text--small text--blue">
                    What’s your ETA? (minutes)
                </h3>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-2">
                            <input id="modal_take_order_time_val" type="text" class="form-control">
                        </div>
                        <div class="col-xs-8 col-md-4">
<!--                            <p class="modal__select--text text text--blue">minutes</p>-->
                            <select class="default-select" name="" id="modal_take_order_time">
                                <option value="min">minutes</option>
                                <option value="hours">hours</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal__body">
                <h3 class="modal__title text--small text--blue">
                    Who’s delivering?
                </h3>
                <div class="row">
                    <div class="form-group col-md-8 col-md-offset-2">
                        <input id="modal_take_order_name" type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <p class="text--small text--blue-opacity">
                        ETA: (by google maps from your office)
                    </p>
                    <p id="modal_take_order_duration" class="text--large text--bold text--red">

                    </p>
                </div>

                <button id="take_order_btn" class="main-btn main-btn--black w100">Accept Delivery!</button>
            </div>
            <div class="modal__success">
                <?= Html::img('@web/img/icon_accept_delivery.svg', ['class' => 'modal__success--img']); ?>
                <p class="sub-text text--white">
                    Delivery Accepted!
                    <br>
                    <span class="text--small text--white-opacity">Away you go!</span>
                </p>
            </div>
        </div>
    </div>
</div>