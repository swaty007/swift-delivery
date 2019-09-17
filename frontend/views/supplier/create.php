<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create supplier';
?>
<section class="supplier">
    <div class="container">
        <h1 class="sub-title text--blue">Delivery Service<br>
            Application</h1>
        <p class="text text--small">Name & Location</p>
        <hr class="line">

        <?php $form = ActiveForm::begin(['id' => 'form-create-supplier', 'class' => 'access__form']); ?>
        <?= $form->field($model, 'password')->label(false)->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->label(false)->passwordInput() ?>

        <div class="fileContainer">
            <img class="fileContainer__img" src="" />
            <p class="fileContainer__text--select text--blue-opacity text--small">Upload new image here <?= Html::img('@web/img/icon_upload.svg', ['class' => 'fileContainer__img--icon']); ?></p>
            <input type="file" name="file" >
        </div>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</section>
