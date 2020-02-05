<?php

namespace frontend\models;


use common\models\Order;
use common\models\Rating;
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
    public $order_id;
    public $supplier_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stars', 'friendly', 'fulfilled', 'onTime', 'again', 'supplier_id', 'order_id', 'supplier_id'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    public function rate() {
        if(!$this->validate()) {
            return false;
        }

        $rating = new Rating();
        $rating->rating = $this->stars;
        $rating->is_friendly = $this->friendly;
        $rating->is_on_time = $this->onTime;
        $rating->is_fulfilled = $this->fulfilled;
        $rating->order_id = $this->order_id;
        $rating->comment = $this->comment;
        $rating->supplier_id = $this->supplier_id;

        return $rating->save();
    }
}
