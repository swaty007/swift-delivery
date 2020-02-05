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
class SupplierEditForm extends Model
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

    public $web_url;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id', 'zip', 'address', 'name', 'terms', 'product_name'], 'required'],
            ['supplier_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => 'common\models\User'],
            [['name', 'product_name'], 'string', 'max' => 50, 'min' => 2],
            [['address', 'address_2'], 'string', 'max' => 60],
            [['items'], 'safe'],
            [['web_url'], 'url'],
            ['terms', 'compare', 'compareValue' => 1, 'type' => 'number', 'operator' => '==', 'message' => 'Please, accept terms of use.'],
            [['logo', 'product_image'], 'file', 'extensions' => 'png, jpg'],
            ['zip', 'exist', 'targetAttribute' => 'zipcode', 'targetClass' => 'common\models\Zipcode', 'message' => 'This zip is not supported.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function edit()
    {
        if (!$this->validate()) {
            return null;
        }

        $gm = new GoogleMaps();

        $apiResult = $gm->getLatLng($this->address . ' ' . $this->address_2);

        if ($apiResult['success'] == false) {
            $this->addError('address', $apiResult['message']);
            return null;
        }

        try {
            $supplier = Supplier::find()->where(['supplier_id' => $this->supplier_id])->one();

            $supplier->supplier_id = $this->supplier_id;
            $supplier->name = $this->name;
            $supplier->zip = $this->zip;
            $supplier->address = $this->address;
            $supplier->address_2 = $this->address_2;
            $supplier->website = $this->web_url;
            $supplier->product_name = $this->product_name;

            if (!$this->saveImages($supplier)) {
                throw new Exception("Error while saving file");
            }

            $this->processGiftItems($supplier);

            $supplier->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function processGiftItems(Supplier $supplier)
    {
        $allowedGiftItemsIds = array_keys(ArrayHelper::index(Product::getActiveList(), 'value'));
        SupplierItemRelation::deleteAll(['supplier_id' => $this->supplier_id]);

        foreach ($this->items as $giftItem) {
            if (!in_array($giftItem, $allowedGiftItemsIds)) {
                continue;
            }

            $relation = new SupplierItemRelation();
            $relation->supplier_id = $supplier->supplier_id;
            $relation->item_id = (int)$giftItem;
            $relation->save();
        }
    }

    private function saveImages(Supplier $supplier)
    {
        try {
            if (!is_null($this->logo)) {
                if (!empty($supplier->oldAttributes['logo'])) {
                    unlink(Yii::$app->params['uploadsDir'] . $supplier->oldAttributes['logo']);
                }

                $logoName = 'logo' . time() . $this->supplier_id . '.' . $this->logo->extension;

                $this->logo->saveAs(Yii::$app->params['uploadsDir'] . $logoName);
                $supplier->logo = $logoName;
            }

            if (!is_null($this->product_image)) {
                if (!empty($supplier->oldAttributes['product_image'])) {
                    unlink(Yii::$app->params['uploadsDir'] . $supplier->oldAttributes['product_image']);
                }

                $piName = 'pi' . time() . $this->supplier_id . '.' . $this->product_image->extension;
                $this->product_image->saveAs(Yii::$app->params['uploadsDir'] . $piName);
                $supplier->product_image = $piName;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
