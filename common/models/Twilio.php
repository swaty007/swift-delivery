<?php

namespace common\models;

use Yii;
use yii\db\Exception;

class Twilio {
    static public function sendSms($to, $message) {
        if (Param::getValue('send_notifications') !== '1') {
            return ;
        }

        try {
            Yii::$app->Yii2Twilio->initTwilio()->account->messages->create(
                $to,
                [
                    "From" => Yii::$app->params['twilio_phone_number'],
                    "Body" => $message,
                ]);
            Log::smsLog($to, $message);
        } catch (\Exception $e) {
            Log::errorLog("SEND SMS ERROR:" . $e->getMessage());
        }
    }

    static public function sendEmailToAdmins($title, $text) {
        if (Param::getValue('send_notifications') !== '1') {
            return ;
        }

        $receivers = explode(',', Param::getValue('email_receivers'));
        foreach ($receivers as $receiver) {
            try {
                Yii::$app->mailer->compose()
                    ->setFrom('info@swiftdeliverydc.com')
                    ->setTo(trim($receiver))
                    ->setSubject($title)
                    ->setHtmlBody($text)
                    ->send();
                Log::emailLog($receiver, $title . '|' . $text);
            } catch (Exception $e) {
                Log::errorLog("SEND EMAIL ERROR:" . $e->getMessage());
            }
        }
    }
}