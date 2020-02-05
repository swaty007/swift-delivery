<?php

use yii\helpers\Html;
?>

<?php \yii\widgets\Pjax::begin(['id' => 'order_status',
    'options' => [
        'class' => '',
        'tag' => 'section'
    ]]); ?>

<?php if ($order->status == \common\models\Order::ORDER_STATUS_NEW) :?>
    <script>
        setTimeout(function () {
            if (typeof $.pjax !== 'undefined') {
                $.pjax.reload({container: "#order_status"});
            }
        }, 7500)
    </script>
    <?php
    echo $this->render('../customer/_searching', ['order' => $order]);
elseif ($order->status == \common\models\Order::ORDER_STATUS_COMPLETE) :
    echo $this->render('../customer/_complete', ['order' => $order, 'model' => $model]);
else : ?>
    <script>
        setTimeout(function () {
            if (typeof $.pjax !== 'undefined') {
                $.pjax.reload({container: "#order_status"});
            }
        }, 7500)
    </script>
    <?php
    echo $this->render('../customer/_onWay', ['order' => $order]);
    endif;?>

<?php \yii\widgets\Pjax::end(); ?>