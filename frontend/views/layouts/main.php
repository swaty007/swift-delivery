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
    <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/img/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
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
<!--    --><?//= Alert::widget() ?>
</div>
<?php if( Yii::$app->session->hasFlash('success') ): ?>
<!--        --><?//= Yii::$app->session->getFlash('success'); ?>
<?php switch (Yii::$app->session->getFlash('success')) {
        case 'Order completed':
            echo $this->render('../components/_alert', ['module' => 'thank']);
            break;
        case 'Order canceled':
            echo $this->render('../components/_alert', ['module' => 'canceled']);
            break;
        case 'Order access':
            echo $this->render('../components/_alert', ['module' => 'order_access']);
            break;
    }
    ; ?>
<?php endif;?>
<header class="header">
    <div class="container">
        <div class="header__logo">

            <a href="<?=Url::toRoute(['site/index']);?>">
                <?= Html::img('@web/img/logo.svg', ['class' => 'header__logo--img']); ?>
                <?php
//                (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index') ?
//                    Html::img('@web/img/logo.svg', ['class' => 'header__logo--img']) :
//                    Html::img('@web/img/logo-small.svg', ['class' => 'header__logo--img']);
//                ?>
            </a>
        </div>
    </div>
</header>

    <?= $content ?>

<nav id="navbar_sd" class="navbar__wrap">
    <div class="container navbar__container">
        <div class="navbar__header">
            <button id="menu_btn" class="collapsed navbar__burger">
                <span class="navbar__burger--line"></span>
                <span class="navbar__burger--line"></span>
                <span class="navbar__burger--line"></span>
            </button>
           <?php if(
                   Yii::$app->controller->id === 'site' &&
                   ( Yii::$app->controller->action->id === 'order' || Yii::$app->controller->action->id === 'order-status' )):?>
           <div class="navbar__step <?= Yii::$app->controller->action->id === 'order' ? 'navbar__step--active' : '';?>">
               <div class="navbar__step--number">
                   1
               </div>
               <div class="navbar__step--text">
                   Enter Information
               </div>
           </div>
           <div class="navbar__step--arrow">
               <?= Html::img('@web/img/icon_arrow_right.svg', ['class' => '']); ?>
           </div>
           <div class="navbar__step <?= Yii::$app->controller->action->id === 'order-status' ? 'navbar__step--active' : '';?>">
               <div class="navbar__step--number">
                   2
               </div>
               <div class="navbar__step--text">
                   Confirmation
<!--                   --><?php //var_dump($this->params);?>
<!--                   --><?php //var_dump(Yii::$app->user->identity);?>
               </div>
           </div>
            <?php else:?>
               <a href="<?=Url::toRoute(['site/order']);?>" class="navbar__btn navbar__btn--red">
                   Start Delivery
               </a>
            <?php endif;?>
<!--            <a class="navbar__brand" href="/">-->
<!--                --><?//= Html::img('@web/img/logo.svg', ['class' => '']); ?>
<!--            </a>-->
        </div>
        <div id="navbar_collapse" class="navbar__collapse">
            <?= Html::img('@web/img/logo-full.svg', ['class' => 'navbar__logo']); ?>
            <ul class="navbar__menu">
                <li class="navbar__item">
                    <a href="<?=Url::toRoute('/info/help-center')?>" class="sub-title text--blue">Help Center</a>
                </li>
                <li class="navbar__item">
                    <a href="<?=Url::toRoute(['/info/contact']);?>" class="sub-title text--blue">Contact</a>
                </li>
                <li class="navbar__item">
                    <hr>
                </li>
                <li class="navbar__item  navbar__item--btn">
                    <a href="<?=Url::toRoute(['site/order']);?>" class="main-btn main-btn--blue">
                        Start Delivery!
                    </a>
                </li>

                <?php if (Yii::$app->user->isGuest):?>
                    <li class="navbar__item">
                        <a href="<?= Url::toRoute(['site/signup']);?>" class="text text--small text--blue-opacity">Delivery Service Sign Up</a>
                    </li>
                    <li class="navbar__item">
                        <a href="<?= Url::toRoute(['site/login']);?>" class="text text--small text--blue-opacity">Delivery Service Login</a>
                    </li>
                <?php else:?>
                    <li class="navbar__item  navbar__item--btn">
                        <a href="<?=Url::toRoute(['supplier/index']);?>" class="main-btn main-btn--black">
                            Delivery Dashboard
                        </a>
                    </li>
                    <li class="navbar__item">
                        <?= Html::a('Logout', ['site/logout'], ['data' => ['method' => 'post'], 'class' => 'text text--small text--blue-opacity']) ?>
                    </li>
                <?php endif;?>

            </ul>
        </div>
        <div class="navbar__bg">
<!--            <div class="navbar__bg--fix"></div>-->
        </div>
    </div>
</nav>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
