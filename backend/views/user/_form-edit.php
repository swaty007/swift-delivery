<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$roleList = [
    '1' => 'Customer',
    '2' => 'Deliver',
    '3' => 'Supplier',
    '4' => 'Admin',
    '5' => 'Super Admin',
];
if(Yii::$app->user->identity->role !== \common\models\User::USER_ROLE_SUPERADMIN) {
    $roleList =
        [
            '1' => 'Customer',
            '2' => 'Deliver',
            '3' => 'Supplier',
            '4' => 'Admin // NOT ALLOWED TO EDIT',
            '5' => 'Super Admin // NOT ALLOWED TO EDIT',
        ];
}
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'phone_number')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+1 (999) 999-9999'])->textInput(['placeholder' => '+1 (__) __-__-__']); ?>
    <?= $form->field($model, 'role')->dropDownList(
        $roleList
    ); ?>
    <?= $form->field($model, 'status')->dropDownList(
        [
            0 => 'Deactivated',
            10 => 'Active',
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
