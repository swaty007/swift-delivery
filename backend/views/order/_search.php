<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplier_id') ?>

    <?= $form->field($model, 'zip') ?>

    <?= $form->field($model, 'status')->dropDownList(
        [
            \common\models\Order::ORDER_STATUS_NEW => 'Waiting for supplier',
            \common\models\Order::ORDER_STATUS_IN_PROGRESS => 'Making delivery',
            \common\models\Order::ORDER_STATUS_DELIVER_NEAR_PLACE => 'Deliver near place',
            \common\models\Order::ORDER_STATUS_DELIVER_AT_PLACE => 'Deliver at place',
            \common\models\Order::ORDER_STATUS_COMPLETE => 'Complete',
            \common\models\Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER => 'Canceled by supplier',
            \common\models\Order::ORDER_STATUS_CANCELLED_BY_DELIVER => 'Canceled by deliver',
            \common\models\Order::ORDER_STATUS_CANCELLED_BY_CUSTOMER => 'Canceled by customer',
            \common\models\Order::ORDER_STATUS_CANCELLED => 'Canceled'
        ]
    ); ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', \yii\helpers\Url::toRoute('/order/index'),['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
