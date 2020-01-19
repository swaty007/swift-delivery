<?php

namespace common\models;

use Yii;

class Twilio {
    static public function sendSms($to, $message) {
        try {
            Yii::$app->Yii2Twilio->initTwilio()->account->messages->create(
                $to,
                [
                    "From" => Yii::$app->params['twilio_phone_number'],
                    "Body" => $message,
                ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
            // todo: implement twilio error logging & handling
        }
    }
}