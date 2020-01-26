<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address_latlng".
 *
 * @property int $id
 * @property string $address
 * @property string $latlng
 */
class AddressLatlng extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'address_latlng';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'latlng'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'latlng' => 'Latlng',
        ];
    }

    public static function tryGetAddressData($addressString) {
        if (($entity = self::findOne(['address' => trim($addressString)]))) {
            return $entity;
        }

        return false;
    }
}
