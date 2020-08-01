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
            'account_sid' => '',
            'auth_key' => '',
        ),
    ],
];
