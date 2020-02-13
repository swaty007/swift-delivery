<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInfoPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Info Pages';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="row">


    <div style="position: relative">
        <p style="position: absolute;right: 0;top:-50px;">
            <?= Html::a('Add Page', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <div class="col-xs-12 col-sm-3">
        <h4>Search</h4>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="col-xs-12 col-sm-9">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'url' => [
                    'label' => 'Url',
                    'value' => function ($data) {
                        return '/info/' . $data['url'];
                    }
                ],
                'title',
                'is_active' => [
                    'label' => 'Is Active',
                    'value' => function ($data) {
                        return $data['is_active'] ? 'Yes' : 'No';
                    }
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
