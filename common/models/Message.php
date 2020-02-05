<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $key
 * @property string $text
 * @property string $type
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 512],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'text' => 'Text',
            'type' => 'Type',
        ];
    }

    public static function getText($key) {
        $instance = self::findOne(['key' => $key]);

        if(is_null($instance)) {
            return '';
        }

        return $instance->text;
    }
}
