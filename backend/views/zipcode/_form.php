<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Zipcode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="zipcode-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_active')->dropDownList([
        '1' => 'Yes',
        '0' => 'No',
    ]); ?>

    <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
