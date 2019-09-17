<?php

/* @var $this yii\web\View */

$this->title = 'Swift Delivery';
?>

<?= $this->render('../components/_cta', ['cta' => 'delivery']); ?>
<?= $this->render('../components/_how-it-works'); ?>
<?= $this->render('../components/_cta', ['cta' => 'signUp']); ?>
<?= $this->render('../components/_cta', ['cta' => 'help']); ?>