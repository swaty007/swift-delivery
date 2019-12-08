<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Zipcode;

/**
 * SearchZipcode represents the model behind the search form of `common\models\Zipcode`.
 */
class SearchZipcode extends Zipcode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['zipcode'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Zipcode::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'zipcode', $this->zipcode]);

        return $dataProvider;
    }
}
