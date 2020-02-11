<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ParamCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Params';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="param-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-xs-12 col-sm-3">
        <h4>Search</h4>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="col-xs-12 col-sm-9">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'label',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],

            ],
        ]); ?>
    </div>
</div>


</div>
