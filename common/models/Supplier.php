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
 * @property string $website
 * @property string $description
 * @property string $product_name
 * @property string $product_image
 * @property string $subscribe_ends
 * @property int $status
 * @property int $is_active
 * @property double $latitude
 * @property double $longitude
 */
class Supplier extends \yii\db\ActiveRecord
{
    /*
     * @var User
     */
    public $user;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    public function __construct($config = [])
    {
        if (!Yii::$app->user->isGuest && $this->supplier_id == Yii::$app->user->getId()) {
            $this->user = Yii::$app->user->identity;
        } else if(!empty($this->supplier_id)) {
            $this->user = User::findOne($this->supplier_id);
        }

        parent::__construct($config);
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
            [['website'], 'string', 'max' => 128],
            [['logo', 'product_image'], 'string', 'max' => 256],
            [['zip'], 'string', 'max' => 20],
            [['address', 'address_2'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 200],
            [['subscribe_ends'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'User ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'zip' => 'Zip',
            'website' => 'Website',
            'address' => 'Address',
            'address_2' => 'Address (Second String))',
            'description' => 'Description',
            'product_name' => 'Product Name',
            'product_image' => 'Product Image',
            'status' => 'Plan',
            'subscribe_ends' => 'Subscribe Ends',
            'is_active' => 'Is Active',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * @return string|null
     */
    public function getImageUrl() {
        if (empty($this->product_image)) {
            return null;
        }

        return Yii::$app->params['webUploadsDir'] . $this->product_image;
    }
}