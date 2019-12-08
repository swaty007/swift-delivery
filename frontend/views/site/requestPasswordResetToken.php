<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Forgot your password?  No sweat.';
?>

<div class="access--blue">
    <div class="container">
        <!--        <div class="row">-->
        <!--            <div class="col-lg-5">-->
        <div class="access__content">
            <h1 class="access__logo">
                <a href="<?=Url::toRoute(['site/index']);?>"></a>
                <?= Html::img('@web/img/logo-on-purple.svg', ['class' => '']); ?>
            </h1>
            <h2 class="access__desc text text--white">
                <?= $this->title;?>
            </h2>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'options' => ['class' => 'access__form']]); ?>

            <?= $form->field($model, 'phone_number')->label(false)->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+1 (999) 999-99-99'])->textInput(['placeholder' => '+1 (___) ___-__-__', 'class' => 'form-control access__phone no-border--top']); ?>

            <div class="form-group">
                <?= Html::submitButton('Text a reset link.', ['class' => 'main-btn main-btn--no-shadow w100 no-border--bottom']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <div class="access__footer flex-center">
                <?= Html::a('I think I remember my password now!', ['site/login'],['class' => 'text text--small text--white']) ?>
            </div>
        </div>
        <!--            </div>-->
        <!--        </div>-->
    </div>
</div>
