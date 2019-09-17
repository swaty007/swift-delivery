<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="position: relative">
    <p style="position: absolute;right: 0;top:-50px;">
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



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
                    'username',
                    'phone_number',
                    'role' => [
                            'label' => 'Role',
                            'format' => 'raw',
                            'value' => function($data) {
                                if (
                                        $data['role'] == \common\models\User::USER_ROLE_SUPPLIER &&
                                        (\common\models\Supplier::findOne(['supplier_id' => $data['id']]))
                                ) {
                                    $data = Html::a(
                                            \common\models\User::getRoleNameFromValue($data['role']),
                                            \yii\helpers\Url::toRoute('/supplier/index?SearchSupplier%5Bsupplier_id%5D=' . $data['id']));
                                } else {
                                    $data = \common\models\User::getRoleNameFromValue($data['role']);
                                }
                                return $data;
                            }
                    ],
                    'status',
                    'created_at:datetime',
                    //'updated_at',
                    //'verification_token',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
