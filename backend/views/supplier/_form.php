<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
$subscribes = [];

foreach (Yii::$app->params['subscribePlans'] as $subscribePlan) {
    $subscribes[$subscribePlan['id']] = $subscribePlan['name'];
}
?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'description', ['options' => ['style' => 'height:100%;']])->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'status')->dropDownList(
                $subscribes
            ); ?>
            <?= $form->field($model, 'subscribe_ends')->widget(\kartik\datetime\DateTimePicker::className(), [
                'name' => 'dp_1',
                'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                //'convertFormat' => true,
                   'value'=> $model->subscribe_ends,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'autoclose' => true,
                    'weekStart' => 1, //неделя начинается с понедельника
                    'todayBtn' => true, //снизу кнопка "сегодня"
                ]
            ]); ?>
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'address_2')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>
            <?php if (!empty($model->product_image)): ?>
                <h3>Product Image</h3>
                <img src="<?= Yii::$app->params['webProjectUrl'] . '/img/uploads/' . $model->product_image ?>"
                     style="height:100px;widht:auto;">
            <?php endif; ?>
            <!--
                <?= $form->field($model, 'product_image')->fileInput() ?>
              -->
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'is_active')->dropDownList([
                '0' => 'No',
                '1' => 'Yes'
            ]) ?>
            <?php if (!empty($model->logo)): ?>
                <h3>Company Logoыс</h3>
                <img src="<?= Yii::$app->params['webProjectUrl'] . '/img/uploads/' . $model->logo ?>"
                     style="height:100px;widht:auto;">
            <?php endif; ?>
            <!--
            <?= $form->field($model, 'logo')->fileInput() ?>
                -->
        </div>

    </div>


    <!--
    <?= $form->field($model, 'latitude')->textInput() ?>

    <?= $form->field($model, 'longitude')->textInput() ?>
    -->
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
