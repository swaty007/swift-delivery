<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Supplier cabinet';
?>
<section class="confirm-supplier">
    <div class="confirm-supplier__container text-center">
        <div class="container">
            <h1 class="title text--white confirm-supplier__title">
                Supplier page
            </h1>

            <p class="text confirm-supplier__text">
                Allowed Orders
            </p>
            <?php \yii\widgets\Pjax::begin(['id' => 'supplier_table_1',
                'options' => [
                    'class' => '',
                    'tag' => 'div'
                ]]); ?>
            <table class="table table-striped table-responsive-lg" style="background: white; width: 100%">
                <tr>
                    <th>Address / Zip</th>
                    <th>Customer</th>
                    <th>Gifts</th>
                    <th></th>
                </tr>
                <?php foreach ($allowed as $item):?>
                    <tr>
                        <td>
                            <?=$item['address']?>
                            <?php if($item['address_2']):?>
                                <?=$item['address_2']?>
                            <?php endif;?>
                            /
                            <b><?=$item['zip']?></b>
                        </td>
                        <td>
                            <?=$item['customer']['username']?>
                            <br>
                            <?=$item['customer']['phone_number']?>
                        </td>
                        <td>
                            <?php $orderTotal = 0;?>
                            <?php foreach($item['orderItems'] as $key => $gift):?>
                                <?=$gift['description']?> - <?=$gift['item_price']?> <b>x<?=$gift['count']?></b><br>
                                <?php $orderTotal += $gift['total_price'];?>
                            <?php endforeach;?>

                            Order Total - <?=$orderTotal?>
                        </td>
                        <td>
                            <a href="?takeOrder=<?=$item['id']?>"><div class="btn btn-default btn-sm" style="cursor: pointer">Take</div></a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
            <p class="text confirm-supplier__text">
                Orders In Progress
            </p>

            <table class="table table-striped table-responsive-lg" style="background: white; width: 100%">
                <tr>
                    <th>Status</th>
                    <th>Address / Zip</th>
                    <th>Customer</th>
                    <th>Gifts</th>
                    <th></th>
                </tr>
                <?php foreach ($inProgress as $item):?>
                    <tr>
                        <td>
                            <?=\common\models\Order::getStatusTextFromStatus($item['status'])?>
                        </td>
                        <td>
                            <?=$item['address']?>
                            <?php if($item['address_2']):?>
                                <?=$item['address_2']?>
                            <?php endif;?>
                            /
                            <b><?=$item['zip']?></b>
                        </td>
                        <td>
                            <?=$item['customer']['username']?>
                            <br>
                            <?=$item['customer']['phone_number']?>
                        </td>
                        <td>
                            <?php $orderTotal = 0;?>
                            <?php foreach($item['orderItems'] as $key => $gift):?>
                                <?=$gift['description']?> - <?=$gift['item_price']?> <b>x<?=$gift['count']?></b><br>
                                <?php $orderTotal += $gift['total_price'];?>
                            <?php endforeach;?>

                            Order Total - <?=$orderTotal?>
                        </td>
                        <td>
                            <a href="?deliverNearPlace=<?=$item['id']?>"><div class="btn btn-default btn-sm" style="cursor: pointer">Deliver near place</div></a>
                            <a href="?cancelSupplier=<?=$item['id']?>"><div class="btn btn-default btn-sm" style="cursor: pointer">Cancel by deliver</div></a>
                            <a href="?cancelDeliver=<?=$item['id']?>"><div class="btn btn-default btn-sm" style="cursor: pointer">Cancel by supplier</div></a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>



            <p class="text confirm-supplier__text">
                Completed Orders
            </p>

            <table class="table table-striped table-responsive-lg" style="background: white; width: 100%">
                <tr>
                    <th>Status</th>
                    <th>Address / Zip</th>
                    <th>Customer</th>
                    <th>Gifts</th>
                </tr>
                <?php foreach ($finished as $item):?>
                    <tr>
                        <td>
                            <?=\common\models\Order::getStatusTextFromStatus($item['status'])?>
                        </td>
                        <td>
                            <?=$item['address']?>
                            <?php if($item['address_2']):?>
                                <?=$item['address_2']?>
                            <?php endif;?>
                            /
                            <b><?=$item['zip']?></b>
                        </td>
                        <td>
                            <?=$item['customer']['username']?>
                            <br>
                            <?=$item['customer']['phone_number']?>
                        </td>
                        <td>
                            <?php $orderTotal = 0;?>
                            <?php foreach($item['orderItems'] as $key => $gift):?>
                                <?=$gift['description']?> - <?=$gift['item_price']?> <b>x<?=$gift['count']?></b><br>
                                <?php $orderTotal += $gift['total_price'];?>
                            <?php endforeach;?>

                            Order Total - <?=$orderTotal?>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
            <script>
                setTimeout(function () {
                    if (typeof $.pjax !== 'undefined') {
                        $.pjax.reload({container: "#supplier_table_1"});
                    }
                }, 5000);
            </script>
            <?php \yii\widgets\Pjax::end(); ?>

        </div>
    </div>
    <?= $this->render('../components/_cta', ['module' => 'help-blue']); ?>
</section>

