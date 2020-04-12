<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model frontend\models\SupplierForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Create supplier';
?>
<?= $this->render('../components/_alert', ['module' => 'available']); ?>
<section class="supplier">
    <div class="container">
        <h1 class="sub-title text--blue text-center supplier__h1">Delivery Service<br>
            Application</h1>
        <p class="text text--small text-center text--green text--bold">Name & Location</p>
        <hr class="line">

        <?php $form = ActiveForm::begin(['id' => 'form-create-supplier', 'enableAjaxValidation' => true]);//, 'enableAjaxValidation' => true ?>
        <!--        --><? //= $form->field($model, 'password')->label(false)->passwordInput() ?>
        <!--        --><? //= $form->field($model, 'password_repeat')->label(false)->passwordInput() ?>

        <?= $form->field($model, 'name')->textInput()->label('Company Name:'); ?>

        <div class="row flex-center">
            <div class="col-xs-8">
                <?= $form->field($model, 'zip',['enableAjaxValidation' => true])->textInput(['id' => 'zip_validate'])->label('Zip Code:'); ?>
            </div>
            <div class="col-xs-4">
                <p id="zip_validate_status" class="supplier__text">
                    Enter zip code to
                    check availability
                </p>
            </div>
        </div>
        <?= $form->field($model, 'address')->textInput()->label('Address:'); ?>
        <?= $form->field($model, 'address_2')->textInput(['placeholder' => 'Addres Line 2 (optional)'])->label(false); ?>
        <?= $form->field($model, 'web_url')->textInput()->label('Website URL:'); ?>

        <div class="form-group supplier__logo">
            <label class="control-label">Upload company logo:</label>
            <p class="supplier__text supplier__text--margin">
                Recommended format: Square & less than 2MB
            </p>
            <div class="fileContainer">
                <img class="fileContainer__img" src=""/>
                <p class="fileContainer__text--select text--blue-opacity text--small">Upload new image
                    here <?= Html::img('@web/img/icon_upload.svg', ['class' => 'fileContainer__img--icon']); ?></p>
                <!--            <input type="file" name="file" >-->
                <?= $form->field($model, 'logo', ['options' => [
                    'tag' => false, // Don't wrap with "form-group" div
                ]])->fileInput(['class'=>'fileContainer__input'])->label(false) ?>
            </div>
        </div>

        <h3 class="sub-text text--blue text--small text-center text--bold">
            What cannabis products do you sell?
        </h3>
        <br>
        <div class="item__wrap">
            <?php foreach ($gifts as $item):?>
                <div class="item">
                    <?= Html::img($item['image'], ['class' => 'item__img']); ?>
                    <div class="item__content">
                        <h3 class="item__title text--small text--blue"><?=$item['name'];?></h3>
                        <p class="item__desc text--blue-opacity"></p>
                    </div>
                    <?= $form->field($model, 'items', [
                            'selectors' => ['input' => "input[name='SupplierForm[items][]']"],
                        'options' => ['class' => 'default-checkbox__container']])
                        ->checkbox(['name'=>'SupplierForm[items][]',  'value' => $item['id'], 'uncheck' => null, 'class' => 'default-checkbox'])
                        ->label(false)->error(false); ?>
                </div>
            <?php endforeach;?>
        </div>

        <p class="text text--small text--blue-opacity">
            Because cannabis has to be gifted, please upload an image of and provide a name for the product customers
            are paying for.
        </p>
        <br>
        <?= $form->field($model, 'product_name')->textInput()->label('Product Name:'); ?>
        <div class="form-group supplier__product">
            <label class="control-label">Upload product image:</label>
            <p class="supplier__text supplier__text--margin">
                Recommended format: Square & less than 2MB
            </p>
            <div class="fileContainer">
                <img class="fileContainer__img" src=""/>
                <p class="fileContainer__text--select text--blue-opacity text--small">Upload new image
                    here <?= Html::img('@web/img/icon_upload.svg', ['class' => 'fileContainer__img--icon']); ?></p>
                <!--            <input type="file" name="file" >-->
                <?= $form->field($model, 'product_image', ['options' => [
                    'tag' => false, // Don't wrap with "form-group" div
                ]])->fileInput(['class'=>'fileContainer__input'])->label(false); ?>
            </div>
        </div>


        <h3 class="sub-text text--green text--small text-center">
            Select your monthly price plan
        </h3>
        <div class="plan__wrap">

        <?php foreach (Yii::$app->params['subscribePlans'] as $key => $plan):?>
            <?php
                $template = '{input}<div class="plan__container"><div class="plan__header"><h3 class="plan__title sub-text">'
                    .$plan["name"].
                    '</h3><p class="text--green text--small"><strong>$'
                    .$plan['pricePerMonth'].
                    '</strong><span class="plan__desc text--blue-opacity">/mo</span></p></div><p class="plan__desc text--blue-opacity">- ';

                if ($plan['dealsPerMonth'] != 9999) {
                    $template .= 'Up to <strong>' . $plan['dealsPerMonth'].
                    ' deliveries</strong> a month</p></div>';
                } else {
                    $template .= '<strong>Unlimited deliveries</strong></p></div>';
                }
            ?>
                <?= $form->field($model, 'plan', ['selectors' => ['input' => "input[name='SupplierForm[plan][]']"],'options' => ['class' => 'plan']])
                    ->radio(['name'=>'SupplierForm[plan]',
                        'value' => $plan['id'],
                        'uncheck' => null,
                        'class' => 'plan__input',
                        'template' => $template,
                    ])
                    ->label(false)->error(false); ?>
        <?php endforeach;?>
        </div>
        <div class="supplier__terms">
            <?= $form->field($model, 'terms', ['options' => ['class' => 'default-checkbox__container']])
                ->checkbox(['value' => 1, 'uncheck' => null, 'class' => 'default-checkbox'])
                ->label(false)->error(false); ?>
            <p class="text text--small text--blue-opacity supplier__terms--text">
                My company will follw Swift Delivery’s
                <a href="<?=Url::toRoute(['/info/supplier-code-of-ethics']);?>" target="_blank" class="text--bold text--blue-opacity">
                    Code of Ethics
                    <?= Html::img('@web/img/icon_share_link.svg', ['class' => 'supplier__terms--img']); ?>
                </a>
            </p>
        </div>
        <!--
        <div class="supplier__core">
            <p class="text text--small text--blue-opacity supplier__core--text">
                - Core value 1
            </p>
            <p class="text text--small text--blue-opacity supplier__core--text">
                - Core value 2
            </p>
            <p class="text text--small text--blue-opacity supplier__core--text">
                - Core value 3
            </p>
        </div>
        -->
        <div class="form-group">
            <?= Html::submitButton('Submit Application', ['class' => 'main-btn main-btn--blue']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</section>
