<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchUser */
/* @var $form yii\widgets\ActiveForm */
$roleList = [
    '1' => 'Customer',
    '2' => 'Deliver',
    '3' => 'Supplier',
    '4' => 'Admin',
    '5' => 'Super Admin',
];
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'phone_number') ?>

    <?= $form->field($model, 'role')->dropDownList($roleList); ?>

    <?= $form->field($model, 'status')->dropDownList(
        [
            0 => 'Deactivated',
            10 => 'Active',
        ]
    ) ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
