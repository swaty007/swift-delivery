<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zipcode".
 *
 * @property int $id
 * @property int $is_active
 * @property string $zipcode
 */
class Zipcode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zipcode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['zipcode'], 'string', 'max' => 32],
            [['zipcode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_active' => 'Is Active',
            'zipcode' => 'Zipcode',
        ];
    }
}
