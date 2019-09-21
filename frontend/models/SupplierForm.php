<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Supplier;

/**
 * Signup form
 */

class SupplierForm extends Model
{
    public $supplier_id;
    public $name;

    public $zip;
    public $address;
    public $address_2;
    public $logo;
    public $product_name;
    public $product_image;
    public $description;
    public $status;
    public $is_active;
    public $latitude;
    public $longitude;

    public $web_url;


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
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

//        $user = new User();
//        $user->username = $this->phone_number;
//        $user->phone_number = $this->phone_number;
//        $user->role = User::USER_ROLE_SUPPLIER;
//        $user->setPassword($this->password);
//        $user->generateAuthKey();
//        $userSave = $user->save();

//        return $userSave;
    }

}
