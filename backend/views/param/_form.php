<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Param */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="param-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?=$model->label?></p>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
