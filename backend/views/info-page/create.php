<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InfoPage */

$this->title = 'Create Info Page';
$this->params['breadcrumbs'][] = ['label' => 'Info Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
