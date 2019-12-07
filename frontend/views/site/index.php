<?php

/* @var $this yii\web\View */

$this->title = 'Swift Delivery';
?>

<?= $this->render('../components/_cta', ['module' => 'delivery']); ?>
<?= $this->render('../components/_how-it-works'); ?>
<?= $this->render('../components/_cta', ['module' => 'signUp']); ?>
<?= $this->render('../components/_cta', ['module' => 'help-green']); ?>