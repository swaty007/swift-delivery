<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchSupplier */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                    'supplier_id' => [
                        'format' => 'raw',
                        'label' => 'User',
                        'value' => function ($data) {
                            $supplier = '';
                            $supplier .= '<a href="' .
                                \yii\helpers\Url::toRoute('/user/update/?id=') .
                                $data['supplier_id'] . '">' . $data['supplier_id'] . '</a>';
                            return $supplier;
                        }
                    ],
                    'name',
                    'zip',
                    //'description',
                    //'product_name',
                    //'product_image',
                    'status' => [
                            'label' => 'Subscribe Plan',
                        'value' => function($data) {
                            $subscribes = \yii\helpers\ArrayHelper::getColumn(Yii::$app->params['subscribePlans'], 'name');
                            return $subscribes[$data->status];
                        }
                    ],
                    'is_active' => [
                            'label' => 'Is Active',
                        'value' => function($data) {
                            $is_active = ['0' => 'No', '1' => 'Yes'];
                            return $is_active[$data->is_active];
                        }
                    ],
                    //'latitude',
                    //'longitude',
                    'date_created',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>


</div>
