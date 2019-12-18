<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $order
 * @property string $display_price
 * @property int $is_active
 * @property array $productOptions
 */
class Product extends \yii\db\ActiveRecord
{
    public $productImage;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'is_active'], 'integer'],
            [['display_price'], 'number'],
            [['name'], 'string', 'max' => 80],
            [['image'], 'string', 'max' => 256],
            [['productOptions'], 'safe'],
            [['productImage'], 'file', 'extensions' => 'png, jpg, svg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'order' => 'Order',
            'display_price' => 'Display Price',
            'is_active' => 'Is Active',
            'productOptions' => 'Options',
        ];
    }

    public static function getActiveList() {
        $data = self::find()->with('productOptions')->where(['is_active' => 1])->orderBy('order')->asArray()->all();

        foreach ($data as $key => $item) {
            $data[$key]['image'] = '/img/uploads/' . $item['image'];
        }

        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOptions()
    {
        return $this->hasMany(ProductOption::class, ['product_id' => 'id']);
    }

    public function setProductOptions(array $productOptions) {
        $this->productOptions = $productOptions;
    }

    public function beforeSave($insert)
    {
        if (!$insert) {
            ProductOption::deleteAll(['product_id' => $this->id]);
        }

        $minPrice = $this->productOptions[0]['price'];

        if ($this->productImage) {
            if(!empty($this->oldAttributes['image']))
            {
                unlink(Yii::$app->params['uploadsDir'] . $this->oldAttributes['image']);
            }
            $pimgName =  'prodimg' . time() . $this->id . '.' . $this->productImage->extension;
            $this->productImage->saveAs(Yii::$app->params['uploadsDir'] . $pimgName);
            $this->image = $pimgName;
        } else {
            $this->image = $this->oldAttributes['image'];
        }

        foreach ($this->productOptions AS $key => $optionData) {
            $option = new ProductOption();
            $option->product_id = $this->id;
            $option->order = $key;
            $option->name = $optionData['name'];
            $option->price = $optionData['price'];

            if($option->price < $minPrice) {
                $minPrice = $option->price;
            }

            $option->save();
        }

        $this->display_price = $minPrice;

        return parent::beforeSave($insert);
    }
}
