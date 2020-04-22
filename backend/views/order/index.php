<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="position: relative">
        <p style="position: absolute;right: 0;top:-50px;">
            <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
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
                    'customer_id' => [
                        'format' => 'raw',
                        'label' => 'Customer',
                        'value' => function ($data) {
                                $customer = '';
                                $customer .= $data['customer']['username'] . '<br>' . $data['customer']['phone_number'];
                                return $customer;
                            }
                    ],
                    'supplier_id' => [
                        'format' => 'raw',
                        'label' => 'Supplier',
                        'value' => function ($data) {
                                $supplier = '';
                                $supplier .= $data['supplier']['name'] . ' ( <a href="' .
                                     \yii\helpers\Url::toRoute('/supplier/update/?id=') .
                                    $data['supplier']['id'] . '">' . $data['supplier']['id'] . '</a> ) ';
                                return $supplier;
                            }
                    ],
                    'zip',
                    'address',
                    'address_2' => [
                            'format' => 'raw',
                            'value' => function ($data) {
                                $products = \common\models\OrderItem::findAll(['order_id' => $data['id']]);
                                $data = '';
                                $total = 0;

                                foreach ($products as $product) {
                                    $data.= $product->description . ' ' . $product->item_price . '$' . ' x' . $product->count . '<br>';
                                    $total+=(int)$product->total_price;
                                }

                                $data.= 'Total: '.$total.'$';

                                return $data;
                            }
                    ],
                    //'description',
                    //'longitude',
                    //'weblink',
                   'status' => [
                     'value' => function ($data) {
                        return \common\models\Order::findOne($data['id'])->getStatusDescription();
                     },
                     'label' => 'Status'
                   ],
                    //'created_at',

                    ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
                ],
            ]); ?>
        </div>
    </div>
</div>
