<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'uploadsDir' => Yii::getAlias('@frontend') . '/web/img/uploads/',
    'webProjectUrl' => 'http://swift-delivery.infinitum.tech',
    'webUploadsDir' => '/img/uploads/',
    'googleMapsApiKey' => 'AIzaSyAPc-Tmoxe8loUBzd2d9osUSIxbEurzWSg',
    'supplierSessionDurationDays' => 3,
    'twilio_phone_number' => '12052559366',
    'subscribePlans' => [
        [
            'name' => 'Basic',
            'pricePerMonth' => 1000,
            'dealsPerMonth' => 150,
            'id' => 1
        ],
        [
            'name' => 'Advanced',
            'pricePerMonth' => 2000,
            'dealsPerMonth' => 250,
            'id' => 2
        ],
        [
            'name' => 'Gold',
            'pricePerMonth' => 2500,
            'dealsPerMonth' => 9999,
            'id' => 3
        ],
    ],
    'giftItems' => [
        [
            'name' => 'Flower',
            'img' => '@web/img/icon_flower.svg',
            'value' => 1,
            'price' => 75
        ],
        [
            'name' => 'Vape Carts',
            'img' => '@web/img/icon_vape.svg',
            'value' => 2,
            'price' => 80
        ],
        [
            'name' => 'Edibles',
            'img' => '@web/img/icon_edibles.svg',
            'value' => 3,
            'price' => 150
        ],
        [
            'name' => 'PreRoll',
            'img' => '@web/img/icon_preroll.svg',
            'value' => 4,
            'price' => 420
        ],
        [
            'name' => 'Concentrates',
            'img' => '@web/img/icon_concentrates.svg',
            'value' => 5,
            'price' => 90
        ]
    ],
    'timeToTakeOrder' => 120 // seconds
];
