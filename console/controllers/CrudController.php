<?php

namespace console\controllers;

use common\models\Message;
use common\models\Supplier;
use common\models\Twilio;
use common\models\User;
use yii\console\Controller;

/**
 * CrudController
 */
class CrudController extends Controller
{
    public function actionUnsubscribe() {
        $suppliers = Supplier::find()->where(['<', 'subscribe_ends', date('Y-m-d H:i:s', time())])->andWhere(['is_active' => 1])->all();

        foreach ($suppliers as $supplier) {
            $number = User::find()->where(['id' => $supplier->supplier_id])->one()->phone_number;
            Twilio::sendSms($number, Message::getText('delivery_plan_requested_sms'));
            $supplier->is_active = 0;
            $supplier->save();
        }
    }
}
