<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Zipcode */

$this->title = 'Update Zipcode: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Zipcodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="zipcode-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
