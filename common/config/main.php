<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'Yii2Twilio' => array(
            'class' => 'filipajdacic\yiitwilio\YiiTwilio',
            'account_sid' => 'ACf40523f682320141af2e85b386f44c65',
            'auth_key' => '9a564caf79a87500f1a5c4bfc328c11e',
        ),
    ],
];
