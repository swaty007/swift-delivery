<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "info_page".
 *
 * @property int $id
 * @property string $url
 * @property string $body
 * @property string $title
 * @property int $is_active
 */
class InfoPage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'info_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['body'], 'string'],
            [['is_active'], 'integer'],
            [['url', 'title'], 'string', 'max' => 100],
            [['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'body' => 'Body',
            'is_active' => 'Is Active',
        ];
    }
}
