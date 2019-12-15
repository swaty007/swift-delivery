<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchProduct */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="position: relative">
        <p style="position: absolute;right: 0;top:-50px;">
            <?= Html::a('Add Product', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <h4>Search</h4>
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div class="col-xs-12 col-sm-9">
            <h4>List</h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'label' => 'Image',
                        'format' => 'raw',
                        'value' => function($data){
                            return Html::img(Yii::$app->params['webProjectUrl'] . '/img/uploads/' . $data->image, [
                                'style' => 'height:40px;width:auto'
                            ]);
                        },
                    ],
                    'name',
                    'display_price',
                    'order',
                    'is_active' => [
                        'label' => 'Is active',
                        'value' => function ($data) {
                                return $data->is_active ? 'Yes' : 'No';
                        }
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>

</div>
