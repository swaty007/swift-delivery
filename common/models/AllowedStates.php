<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "allowed_states".
 *
 * @property int $id
 * @property string $state_name
 * @property int $is_active
 */
class AllowedStates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allowed_states';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['state_name'], 'string', 'max' => 155],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state_name' => 'State Name',
            'is_active' => 'Is Active',
        ];
    }
}
