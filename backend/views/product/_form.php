<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'order')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <?php if (!empty($model->image)):?>
                <img src="<?=Yii::$app->params['webProjectUrl'] . '/img/uploads/' . $model->image?>" style="height:100px;widht:auto;">
            <?php endif;?>
            <?= $form->field($model, 'image')->fileInput() ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'is_active')->dropDownList([
                '0' => 'No',
                '1' => 'Yes'
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'productOptions')->widget(MultipleInput::className(), [
        'max'               => 20,
        'columns' => [
            [
                'name'  => 'name',
                'title' => 'Name',
                'defaultValue' => '',
            ],
            [
                'name'  => 'price',
                'title' => 'Price',
                'defaultValue' => '',
            ]
        ],
        'min'               => 1,
        'allowEmptyList'    => false,
        'enableGuessTitle'  => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
    ])->label('Product Options'); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
