<?php

namespace frontend\models;


use common\models\Order;
use yii\base\Model;

/**
 * Signup form
 */
class RatingForm extends Model
{
    public $friendly;
    public $fulfilled;
    public $onTime;
    public $again;
    public $stars;
    public $comment;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['phone_number', 'name', 'zip', 'address'], 'required'],
//            ['phone_number', 'trim'],
//            ['phone_number', 'match', 'pattern' => '/^\+1\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Incorrect phone number'],
//            [['name'], 'string', 'max' => 50, 'min' => 2],
//            [['address', 'address_2'], 'string', 'max' => 80],
//            [['description'], 'string', 'max' => 200],
//            ['zip', 'string'],
            ////  ['zip', 'exist', 'targetAttribute' => 'zipcode', 'targetClass' => 'common\models\Zipcode', 'message' => 'This zip is not supported.'],
        ];
    }




}
