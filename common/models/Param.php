<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "param".
 *
 * @property int $id
 * @property string $key
 * @property string $label
 * @property string $value
 */
class Param extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'param';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key'], 'string', 'max' => 20],
            [['label'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 512],
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
            'label' => 'Label',
            'value' => 'Value',
        ];
    }

    static public function getValue($key) {
        return self::findOne(['key' => $key])->value;
    }
}
