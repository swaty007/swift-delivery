<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $name
 * @property string $logo
 * @property string $zip
 * @property string $address
 * @property string $address_2
 * @property string $description
 * @property string $product_name
 * @property string $product_image
 * @property int $status
 * @property int $is_active
 * @property double $latitude
 * @property double $longitude
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id'], 'required'],
            [['supplier_id', 'status', 'is_active'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'product_name'], 'string', 'max' => 100],
            [['logo', 'product_image'], 'string', 'max' => 256],
            [['zip'], 'string', 'max' => 20],
            [['address', 'address_2'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'zip' => 'Zip',
            'address' => 'Address',
            'address_2' => 'Address 2',
            'description' => 'Description',
            'product_name' => 'Product Name',
            'product_image' => 'Product Image',
            'status' => 'Status',
            'is_active' => 'Is Active',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }
}
