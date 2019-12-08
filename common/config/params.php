<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'uploadsDir' => Yii::getAlias('@frontend') . '/web/img/uploads/',
    'giftItems' =>
        [
            [
                'name'  => 'Flower',
                'img'   => '@web/img/icon_flower.svg',
                'value' => 1,
                'price' => 75
            ],
            [
                'name'  => 'Vape Carts',
                'img'   => '@web/img/icon_vape.svg',
                'value' => 2,
                'price' => 80
            ],
            [
                'name'  => 'Edibles',
                'img'   => '@web/img/icon_edibles.svg',
                'value' => 3,
                'price' => 150
            ],
            [
                'name'  => 'PreRoll',
                'img'   => '@web/img/icon_preroll.svg',
                'value' => 4,
                'price' => 420
            ],
            [
                'name'  => 'Concentrates',
                'img'   => '@web/img/icon_concentrates.svg',
                'value' => 5,
                'price' => 90
            ]
        ]
];
