<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
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
    <link rel="manifest" href="/frontend/web/manifest.json">
    <script>
        function urlBase64ToUint8Array(base64String) {
            var padding = '='.repeat((4 - base64String.length % 4) % 4);
            var base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            var rawData = window.atob(base64);
            var outputArray = new Uint8Array(rawData.length);

            for (var i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
        if ('serviceWorker' in navigator || 'PushManager' in window) {
            window.addEventListener('load', function() {
                return;
                navigator.serviceWorker.register('/sw.js', { scope: '/' }).then(function(reg) {
                    // Регистрация успешна
                    console.log('ServiceWorker registration successful with scope: ', reg.scope);
                    console.log(reg,'reg');

                    navigator.serviceWorker.addEventListener('message', function(event) {
                        console.log(event);
                    });
                    navigator.serviceWorker.addEventListener('onmessage', function(event) {
                        console.log(event);
                    });




                    const subscribeOptions = {
                        applicationServerKey: urlBase64ToUint8Array(
                            'BLAlJkwiyef6Z9FEpOhsTMTZ2dIpDSkL35baoHnijsMttGhENQYOKRl4WbLAuU_2Pg0D1OPWdxzObdbQfQoiv-M'
                        ),
                        userVisibleOnly: true
                    };
                    // fX_Ack7iQAfM35mKy3iRyUvV4ThzWsTSwFO6RZv54tg

                    reg.pushManager.subscribe(subscribeOptions)
                        .then(function(pushSubscription) {
                        console.log('Received PushSubscription: ', pushSubscription);
                        console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
                        return pushSubscription;
                    });


                }).catch(function(err) {
                    // Регистрация не успешна
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                // new Notification("Hi there!");
                navigator.serviceWorker.getRegistration().then(function(reg) {
                    console.log(reg,'reg Notification');
                    var options = {
                        body: 'Here is a notification body!',
                        icon: 'img/icon_settings.svg',
                        vibrate: [100, 50, 100],
                        data: {
                            dateOfArrival: Date.now(),
                            primaryKey: 1
                        },
                        actions: [
                            {action: 'explore', title: 'Explore this new world',
                                icon: 'img/icon_settings.svg'},
                            {action: 'close', title: 'Close notification',
                                icon: 'img/icon_settings.svg'},
                        ]
                    };
                    //reg.showNotification('Hello world!', options);
                });
            } else {
                Notification.requestPermission();
            }
        }
    </script>
    <meta name="application-name" content="Swift Delivery">
    <meta name="msapplication-TileColor" content="#242221">
    <meta name="msapplication-TileImage" content="/img/logo-144.png">
<!--    <meta name="msapplication-config" content="/weblx/assets/browserconfig.xml">-->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<?php
//NavBar::begin([
//    'brandLabel' => Yii::$app->name,
//    'brandUrl' => Yii::$app->homeUrl,
//    'options' => [
//        'class' => 'navbar-inverse ',//navbar-fixed-top
//    ],
//]);
//$menuItems = [
//    ['label' => 'Home', 'url' => ['/site/index']],
//    ['label' => 'About', 'url' => ['/site/about']],
//    ['label' => 'Contact', 'url' => ['/site/contact']],
//];
//if (Yii::$app->user->isGuest) {
//    $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
//    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
//} else {
//    $menuItems[] = '<li>'
//        . Html::beginForm(['/site/logout'], 'post')
//        . Html::submitButton(
//            'Logout (' . Yii::$app->user->identity->username . ')',
//            ['class' => 'btn btn-link logout']
//        )
//        . Html::endForm()
//        . '</li>';
//}
//echo Nav::widget([
//    'options' => ['class' => 'navbar-nav navbar-right'],
//    'items' => $menuItems,
//]);
//NavBar::end();
?>




<div class="container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
</div>

<header class="header">
    <div class="container">
        <div class="header__logo">

            <a href="<?=Url::toRoute(['site/index']);?>">
                <?=
                (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index') ?
                    Html::img('@web/img/logo.svg', ['class' => 'header__logo--img']) :
                    Html::img('@web/img/logo-small.svg', ['class' => 'header__logo--img']);
                ?>
            </a>
        </div>
    </div>
</header>


    <?= $content ?>



<template style="display: none;">
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
                        <a href="#" class="main-btn main-btn--sm w-100">
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
                    <label class="default-checkbox__container">
                        <input type="checkbox" class="default-checkbox">
                        <span class="default-checkbox__mark"></span>
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


    <select class="default-select" name="" id="">
        <option value="">7 grams (1/4oz) sativa</option>
        <option value="">7 grams (1/4oz) sativa</option>
        <option value="">7 grams (1/4oz) sativa</option>
    </select>

    <div class="spinner">
        <input id="item_quanitity" data-price="75" value="1" />
        <label class="sub-text text--green" for="item_quanitity">$75.00</label>
    </div>
</template>


<nav id="navbar_sd" class="navbar__wrap">
    <div class="container navbar__container">
        <div class="navbar__header">
            <button id="menu_btn" class="collapsed navbar__btn">
                <span class="navbar__btn--line"></span>
                <span class="navbar__btn--line"></span>
                <span class="navbar__btn--line"></span>
            </button>
            <a class="navbar__brand" href="/">
                <?= Html::img('@web/img/logo.svg', ['class' => '']); ?>
            </a>
        </div>
        <div id="navbar_collapse" class="navbar__collapse">
            <ul class="navbar__menu">
                <li class="navbar__item">
                    <a href="#" class="sub-title text--blue">Help Center</a>
                </li>
                <li class="navbar__item">
                    <a href="#" class="sub-title text--blue">Contact</a>
                </li>
                <li class="navbar__item">
                    <hr>
                </li>
                <li class="navbar__item  navbar__item--btn">
                    <a href="#" class="main-btn">
                        Start Delivery!
                    </a>
                </li>
                <li class="navbar__item">
                    <a href="<?= Url::toRoute(['site/signup']);?>" class="text text--small text--blue-opacity">Delivery Service Sign Up</a>
                </li>
                <li class="navbar__item">
                    <a href="<?= Url::toRoute(['site/login']);?>" class="text text--small text--blue-opacity">Delivery Service Login</a>
                </li>
            </ul>
        </div>
        <div class="navbar__bg">
<!--            <div class="navbar__bg--fix"></div>-->
        </div>
    </div>
</nav>



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
