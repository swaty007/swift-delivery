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
                    'customer_id',
                    'supplier_id',
                    'zip',
                    'address',
                    //'address_2',
                    //'description',
                    //'latitude',
                    //'longitude',
                    //'weblink',
                    //'status',
                    //'created_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
