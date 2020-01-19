<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InfoPage */

$this->title = 'Update Info Page: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Info Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="info-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
