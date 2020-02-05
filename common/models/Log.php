<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $text
 * @property string $date
 * @property string $type
 * @property string $receiver
 */
class Log extends \yii\db\ActiveRecord
{
    const TYPE_ORDER = 'order';
    const TYPE_SMS = 'sms';
    const TYPE_EMAIL = 'email';
    const TYPE_ERROR = 'error';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string', 'max' => 255],
            [['receiver'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'date' => 'Date',
            'receiver' => 'Receiver',
        ];
    }

    static public function orderLog($order_id, $user_id, $text) {
        $log = new Log();
        $log->order_id = $order_id;
        $log->user_id = $user_id;
        $log->text = $text;
        $log->type = self::TYPE_ORDER;
        $log->save();
    }

    static public function emailLog($email, $text)
    {
        $log = new Log();
        $log->receiver = $email;
        $log->text = $text;
        $log->type = self::TYPE_EMAIL;
        $log->save();
    }

    static public function smsLog($phone, $text)
    {
        $log = new Log();
        $log->receiver = $phone;
        $log->text = $text;
        $log->type = self::TYPE_SMS;
        $log->save();
    }

    static public function errorLog($text)
    {
        $log = new Log();
        $log->text = $text;
        $log->type = self::TYPE_ERROR;
        $log->save();
    }
}
