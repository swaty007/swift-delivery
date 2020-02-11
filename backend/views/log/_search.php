<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LogCustomer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'text') ?>

    <?= $form->field($model, 'type')->dropDownList(
        [
            \common\models\Log::TYPE_ORDER => 'Orders',
            \common\models\Log::TYPE_ERROR => 'Errors',
            \common\models\Log::TYPE_EMAIL => 'Emails',
            \common\models\Log::TYPE_SMS => 'SMS',
        ]
    ); ?>

    <?= $form->field($model, 'receiver') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', \yii\helpers\Url::toRoute('/log/index'),['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
