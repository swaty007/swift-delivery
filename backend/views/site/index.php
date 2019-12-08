<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-xs-12" style="padding-bottom: 40px">
        <h2>Dashboard</h2>
    </div>
    <div class="col-xs-12 col-sm-4">
        Total Registered Users: <?= \common\models\User::find()->count()?>
    </div>
    <div class="col-xs-12 col-sm-4">
        Total Customers: <?= \common\models\Customer::find()->count()?>
    </div>
    <div class="col-xs-12 col-sm-4">
        Total Apptoroved Suppliers: <?= \common\models\Supplier::find()->count()?>
    </div>
    <div class="col-xs-12 col-sm-4">
        Total Allowed Zipcodes: <?= \common\models\Supplier::find()->count()?>
    </div>
</div>
