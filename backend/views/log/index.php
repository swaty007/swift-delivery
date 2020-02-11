<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <h4>Search</h4>
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div class="col-xs-12 col-sm-9">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'text',
                    'type',
                    'date',
                    'receiver',
                    'order_id' => [
                        'format' => 'raw',
                        'label' => 'Order',
                        'value' => function ($data) {
                            $output = '<a href="' .
                                \yii\helpers\Url::toRoute('/order/index?SearchOrder[id]=' .
                                $data['order_id']) .
                                '">' .
                                $data['order_id'] .
                                '</a>';
                            return $output;
                        }
                    ],
                    'user_id' => [
                        'format' => 'raw',
                        'label' => 'User',
                        'value' => function ($data) {
                            $output = '<a href="' .
                                \yii\helpers\Url::toRoute('/user/index?SearchUser[id]=' .
                                    $data['user_id']) .
                                '">' .
                                $data['user_id'] .
                                '</a>';
                            return $output;
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>


</div>
