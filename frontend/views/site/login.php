<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form',
//                'fieldConfig' => [
//                'template' => '{label}<div class="col-sm-10">{input}</div><div class="col-sm-10">{error}</div>',
//                'labelOptions' => ['class' => 'col-sm-2 control-label'],
//                ],
            ]); ?>

<!--            --><?//= Html::activeLabel($model, 'password') ?>
<!--            --><?//= Html::activePasswordInput($model, 'password') ?>
<!--            --><?//= Html::error($model, 'password') ?>
            <?= Html::activeLabel($model, 'username', ['label' => 'name']) ?>
            <?= Html::activeTextInput($model, 'username') ?>
            <?= Html::error($model, 'username') ?>


<!--                --><?//= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
