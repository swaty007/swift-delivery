<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
$customer = $model->getCustomer()->one();
$products = \common\models\OrderItem::findAll(['order_id' => $model->id]);
$data = '';
$total = 0;

foreach ($products as $product) {
    $data.= $product->description . ' | price / count - <b>' . $product->item_price . '$' . ' / ' . $product->count . '</b><br>';
    $total+=(int)$product->total_price;
}

$data.= '<br>Total: <b>'.$total.'$</b>';
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            <h4>Order</h4>
            <b>Status:</b> <?=$model->getStatusDescription();?> (<?=$model->status?>)<br>
            <b>Order Created At:</b> <?= $model->created_at?> <br>
            <b>Supplier Link</b> <a href="<?= Yii::$app->params['webProjectUrl'] . '/supplier/show-order?l=' . $model->weblink?>"><?= Yii::$app->params['webProjectUrl'] . '/supplier/show-order?l=' . $model->weblink?></a>

        </div>
        <div class="col-xs-6">
            <h4>Address / Description</h4>
            <b>Zip code:</b> <?= $model->zip?><br>
            <b>Address:</b> <?= $model->address . ' ' . $model->address_2?><br>
            <b>Description:</b> <?= $model->description?><br>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-xs-6">
            <h4>Customer</h4>
            <b>Customer Name:</b> <?= preg_replace("/[^a-zA-Z\s]/", "", $customer->username);?><br>
            <b>Customer Phone Number:</b> <?= $customer->phone_number?><br>

        </div>
        <div class="col-xs-6">
            <h4>Goods</h4>
            <?=$data?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
