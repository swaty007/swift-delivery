<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access--blue">
    <div class="container">
<!--        <div class="row">-->
<!--            <div class="col-lg-5">-->
                <div class="access__content">
                    <h1 class="access__logo"><?= Html::img('@web/img/logo-on-purple.svg', ['class' => '']); ?></h1>
                    <h2 class="access__desc text text--white">
                        <?= $this->title;?>
                    </h2>
                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'access__form'],
//                'fieldConfig' => [
//                'template' => '{label}<div class="col-sm-10">{input}</div><div class="col-sm-10">{error}</div>',
//                'labelOptions' => ['class' => 'col-sm-2 control-label'],
//                ],
                    ]); ?>

                    <!--            --><?//= Html::activeLabel($model, 'password') ?>
                    <!--            --><?//= Html::activePasswordInput($model, 'password') ?>
                    <!--            --><?//= Html::error($model, 'password') ?>
                    <!--            --><?//= Html::activeLabel($model, 'username', ['label' => 'name']) ?>
                    <!--            --><?//= Html::activeTextInput($model, 'username') ?>
                    <!--            --><?//= Html::error($model, 'username') ?>


                    <?= $form->field($model, 'phone_number')->label(false)->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+1 (999) 999-99-99'])->textInput(['placeholder' => '+1 (___) ___-__-__', 'class' => 'form-control no-border--top'])->label(false); ?>

                    <?= $form->field($model, 'password')->label(false)->passwordInput(['class'=>'form-control no-border--r0']) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'main-btn main-btn--no-shadow w100 no-border--bottom', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                    <div class="access__footer flex-center">
                        <?= Html::a('reset it', ['site/request-password-reset'],['class' => 'text text--small text--white']) ?>
                        <?= Html::a('Sign up', ['site/signup'],['class' => 'text text--small text--white']) ?>
                    </div>
                </div>
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
