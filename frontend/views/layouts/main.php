<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse ',//navbar-fixed-top
    ],
]);
$menuItems = [
    ['label' => 'Home', 'url' => ['/site/index']],
    ['label' => 'About', 'url' => ['/site/about']],
    ['label' => 'Contact', 'url' => ['/site/contact']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
?>



<div class="alert alert--blue">
    <div class="container flex-center">
        <?= Html::img('@web/img/icon_alarm.svg', ['class' => 'alert__icon']); ?>
        <p class="text text--small">
            <strong class="text--white">Swift Delivery</strong> is currently only availble in <strong class="text--white">Washington DC.</strong>
        </p>
    </div>
</div>
<div class="alert alert--green">
    <div class="container flex-center">
        <?= Html::img('@web/img/icon_glasses_emoji.png', ['class' => 'alert__icon']); ?>
        <div>
            <p class="text text--small">
                Thank for using <strong class="text--white">Swift Delivery!</strong> Hope to see you again soon.
            </p>
            <a class="text text--small text--white" href="#">Share your love on social!</a>
        </div>
    </div>
</div>
<?= $this->render('../components/_cta', ['cta' => 'delivery']); ?>
<div class="container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
</div>
    <?= $content ?>

<section class="roll text-center">
    <div class="container">
        <h2 class="sub-title text--blue">
            Let’s Roll!
        </h2>
        <p class="sub-text text--green">
            Select your cannabis gift:
        </p>
        <div class="item__wrap">

            <div class="item">
                <?= Html::img('@web/img/icon_flower.svg', ['class' => 'item__img']); ?>
                <div class="item__content">
                    <h3 class="item__title text--small text--blue">Flower</h3>
                    <p class="item__desc text--blue-opacity">
                        starting at <strong class="text--green text--normal">$75.00</strong>
                    </p>
                    <a href="#" class="main-btn main-btn--sm">
                        Select
                    </a>
                </div>
            </div>

            <div class="item">
                <?= Html::img('@web/img/icon_flower.svg', ['class' => 'item__img']); ?>
                <div class="item__content">
                    <h3 class="item__title text--small text--blue">Flower</h3>
                    <p class="item__desc text--blue-opacity"></p>
                </div>
                <label class="checkbox__container">
                    <input type="checkbox" class="checkbox">
                    <span class="checkbox__mark"></span>
                </label>
            </div>

            <div class="item item--green item--center">
                <p class="text text--white">
                    <strong>
                        Coming Soon
                    </strong>
                </p>
            </div>

        </div>
    </div>
</section>

<div class="fileContainer">
    <img class="fileContainer__img" src="">
    <p class="fileContainer__text--select text--blue-opacity text--small">Upload new image here <?= Html::img('@web/img/icon_upload.svg', ['class' => 'fileContainer__img--icon']); ?></p>
    <input type="file" name="file" >
</div>

<?= $this->render('../components/_how-it-works'); ?>
<?= $this->render('../components/_cta', ['cta' => 'signUp']); ?>
<?= $this->render('../components/_cta', ['cta' => 'help']); ?>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
