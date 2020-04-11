<?php

namespace frontend\models;

use common\models\GoogleMaps;
use common\models\Message;
use common\models\Product;
use common\models\SupplierItemRelation;
use common\models\Twilio;
use common\models\Zipcode;
use Yii;
use yii\base\Model;
use common\models\Supplier;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

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
    public $items;
    public $terms;
    public $plan;

    public $web_url;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id', 'zip', 'address', 'name', 'terms', 'product_name'], 'required'],
            ['supplier_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => 'common\models\User'],
            ['supplier_id', 'unique', 'targetAttribute' => 'supplier_id', 'targetClass' => 'common\models\Supplier'],
            [['name', 'product_name'], 'string', 'max' => 50, 'min' => 2],
            [['address', 'address_2'], 'string', 'max' => 60],
            ['plan', 'integer', 'min' => 1, 'max' => 3],
            [['items'], 'safe'],
            [['items', 'plan'], 'required'],
            [['web_url'], 'string', 'max' => 256, 'message' => 'Incorrect site url'],
            ['terms', 'compare', 'compareValue' => 1, 'type' => 'number', 'operator' => '==', 'message' => 'Please, accept terms of use.'],
            [['logo', 'product_image'], 'file', 'extensions' => 'png, jpg'],
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

        $gm = new GoogleMaps();

        $apiResult = $gm->getLatLng($this->address . ' ' . $this->address_2 . ' ' . $this->zip);

        if ($apiResult['success'] == false) {
            $this->addError('address', $apiResult['message']);
            return null;
        }

        if (Zipcode::isBlocked($this->zip)) {
            $this->addError('zip', 'Zipcode is not allowed');
            return null;
        }

        try {
            $supplier = new Supplier();

            $supplier->supplier_id = $this->supplier_id;
            $supplier->name = $this->name;
            $supplier->zip = $this->zip;
            $supplier->address = $this->address;
            $supplier->address_2 = $this->address_2;
            $supplier->website = $this->web_url;
            $supplier->product_name = $this->product_name;
            $supplier->status = $this->plan;

            if (!$this->saveImages($supplier)) {
                throw new Exception("Error while saving file");
            }

            $this->processGiftItems($supplier);

            $supplier->logo = $this->logo;
            $supplier->product_image = $this->product_image;
            $supplier->save();
            Twilio::sendSms(Yii::$app->user->identity->phone_number, Message::getText('supplier_registeration_sms'));
            Twilio::sendEmailToAdmins("Supplier need activation", "a new Delivery Application has been submitted by $supplier->name. Click this <a href='" . Yii::$app->params['webProjectUrl'] . "/backend/web/supplier/update?id=$supplier->id'>link</a> to review the application.");
            return true;
        } catch (\Exception $e) {
            Supplier::deleteAll(['supplier_id' => $this->supplier_id]);
            SupplierItemRelation::deleteAll(['supplier_id' => $this->supplier_id]);
            return false;
        }
    }

    private function processGiftItems(Supplier $supplier)
    {
        $allowedGiftItemsIds = array_keys(ArrayHelper::index(Product::getActiveList(), 'id'));

        foreach ($this->items as $giftItem) {
            if (!in_array($giftItem, $allowedGiftItemsIds)) {
                continue;
            }

            $relation = new SupplierItemRelation();
            $relation->supplier_id = $supplier->supplier_id;
            $relation->item_id = $giftItem;
            $relation->save();
        }
    }

    private function saveImages(Supplier $supplier)
    {
        try {
            if (!empty($supplier->oldAttributes['logo'])) {
                unlink(Yii::$app->params['uploadsDir'] . $supplier->oldAttributes['logo']);
            }

            if (!empty($supplier->oldAttributes['product_image'])) {
                unlink(Yii::$app->params['uploadsDir'] . $supplier->oldAttributes['product_image']);
            }

            if (!is_null($this->logo)) {
                $logoName = 'logo' . time() . $this->supplier_id . '.' . $this->logo->extension;
                $this->logo->saveAs(Yii::$app->params['uploadsDir'] . $logoName);
                $this->logo = $logoName;
            }

            if (!is_null($this->product_image)) {
                $piName = 'pi' . time() . $this->supplier_id . '.' . $this->product_image->extension;
                $this->product_image->saveAs(Yii::$app->params['uploadsDir'] . $piName);
                $this->product_image = $piName;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
