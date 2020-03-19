<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access--black">
    <div class="container">
<!--        <div class="row">-->
<!--            <div class="col-lg-5">-->
                <div class="access__content">
                    <h1 class="access__logo">
                        <a href="<?= Url::toRoute(['site/index']);?>">
<!--                            --><?//= Html::img('@web/img/logo-on-green.svg', ['class' => '']); ?>
                            <?= Html::img('@web/img/logo-white.svg', ['class' => '']); ?>
                        </a>
                    </h1>
                    <h2 class="access__desc text text--white">
                        <?= $this->title;?>
                    </h2>


                    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['class'=>'access__form']]); ?>
                    <?= $form->field($model, 'phone_number',['options' => ['class' => 'form-group']])->label(false)->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+1 (999) 999-9999'])->textInput(['placeholder' => '+1 (___) ___-____','class'=>'form-control access__phone no-border--top']); ?>
                    <?= $form->field($model, 'password')->label(false)->passwordInput(['class'=>'form-control access__password no-border--r0']) ?>
                    <?= $form->field($model, 'password_repeat')->label(false)->passwordInput(['class'=>'form-control access__password no-border--r0']) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Signup', ['class' => 'main-btn main-btn--blue main-btn--no-shadow w100 no-border--bottom', 'name' => 'signup-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <div class="access__footer flex-center flex-center--between">

                        <?= Html::a('Reset it', ['site/request-password-reset'],['class' => 'text text--small text--white']) ?>
                        <?= Html::a('Login', ['site/login'],['class' => 'text text--small text--white']) ?>
                    </div>
                </div>
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
