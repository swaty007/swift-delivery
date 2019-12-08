<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'phone_number')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+1 (999) 999-99-99'])->textInput(['placeholder' => '+1 (__) __-__-__']); ?>
    <?= $form->field($model, 'role')->dropDownList([
        '1' => 'Customer',
        '2' => 'Deliver',
        '3' => 'Supplier',
        '4' => 'Admin',
        '5' => 'Super Admin',
    ]); ?>
    <?= $form->field($model, 'status')->textInput() ?>
    <?= $form->field($model, 'password')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
