<?php

use yii\helpers\Html;

?>

<?php \yii\widgets\Pjax::begin(['id' => 'order_status',
    'options' => [
        'class' => '',
        'tag' => 'section'
    ]]); ?>

<?php if ($order->status == \common\models\Order::ORDER_STATUS_NEW) {
    echo $this->render('../customer/_searching', ['order' => $order]);
} else {
    echo $this->render('../customer/_onWay', ['order' => $order]);
} ?>
    <script>
        setTimeout(function () {
            if (typeof $.pjax !== 'undefined') {
                $.pjax.reload({container: "#order_status"});
            }
        }, 5500)
    </script>
<?php \yii\widgets\Pjax::end(); ?>