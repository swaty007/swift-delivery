<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Supplier cabinet';
?>
<section class="supplier-cab">


    <div class="container">

        <div class="supplier-cab__menu">
            <div class="supplier-cab__username">
                <p class="text--large"><strong>Hi,</strong> Green Label DC!</p>
               <div class="stars">
                   <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                   <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                   <?= Html::img('@web/img/icon_star_full.svg', ['class' => '']); ?>
                   <?= Html::img('@web/img/icon_star_empty.svg', ['class' => '']); ?>
                   <?= Html::img('@web/img/icon_star_empty.svg', ['class' => '']); ?>
               </div>
            </div>
            <a href="#" class="main-btn main-btn--settings">Settings</a>
        </div>
        <br>
        <div class="supplier-cab__monthly">
            <div class="supplier-cab__monthly--left">
                <p class="supplier-cab__text text--blue-opacity">
                    Monthly<br>
                    Earnings:
                </p>
                <h4 class="supplier-cab__monthly--text text--green">
                    $8,500
                </h4>
            </div>
            <div class="supplier-cab__monthly--right">
                <p class="supplier-cab__text text--blue-opacity">Accepted: <strong class="text--green">8</strong></p>
                <p class="supplier-cab__text text--blue-opacity">Earnings: <strong class="text--green">20</strong></p>
            </div>
        </div>
        <br>
        <h2 class="text--blue text">
            Order History:
        </h2>
        <table class="supplier-cab__table">
            <tr>
                <th>Status</th>
                <th>Address / Zip</th>
                <th>Customer</th>
                <th>Gifts</th>
            </tr>
            <?php foreach ($finished as $item): ?>
                <tr>
                    <td>
                        <?= \common\models\Order::getStatusTextFromStatus($item['status']) ?>
                    </td>
                    <td>
                        <?= $item['address'] ?>
                        <?php if ($item['address_2']): ?>
                            <?= $item['address_2'] ?>
                        <?php endif; ?>
                        /
                        <b><?= $item['zip'] ?></b>
                    </td>
                    <td>
                        <?= $item['customer']['username'] ?>
                        <br>
                        <?= $item['customer']['phone_number'] ?>
                    </td>
                    <td>
                        <?php $orderTotal = 0; ?>
                        <?php foreach ($item['orderItems'] as $key => $gift): ?>
                            <?= $gift['description'] ?> - <?= $gift['item_price'] ?> <b>x<?= $gift['count'] ?></b><br>
                            <?php $orderTotal += $gift['total_price']; ?>
                        <?php endforeach; ?>

                        Order Total - <?= $orderTotal ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>











        <p class="text text--blue">
            Allowed Orders
        </p>

        <table class="table table-striped table-responsive-lg" style="background: white; width: 100%">
            <tr>
                <th>Address / Zip</th>
                <th>Customer</th>
                <th>Gifts</th>
                <th></th>
            </tr>
            <?php foreach ($allowed as $item): ?>
                <tr>
                    <td>
                        <?= $item['address'] ?>
                        <?php if ($item['address_2']): ?>
                            <?= $item['address_2'] ?>
                        <?php endif; ?>
                        /
                        <b><?= $item['zip'] ?></b>
                    </td>
                    <td>
                        <?= $item['customer']['username'] ?>
                        <br>
                        <?= $item['customer']['phone_number'] ?>
                    </td>
                    <td>
                        <?php $orderTotal = 0; ?>
                        <?php foreach ($item['orderItems'] as $key => $gift): ?>
                            <?= $gift['description'] ?> - <?= $gift['item_price'] ?> <b>x<?= $gift['count'] ?></b><br>
                            <?php $orderTotal += $gift['total_price']; ?>
                        <?php endforeach; ?>

                        Order Total - <?= $orderTotal ?>
                    </td>
                    <td>
                        <a href="?takeOrder=<?= $item['id'] ?>">
                            <div class="btn btn-default btn-sm" style="cursor: pointer">Take</div>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p class="text text--blue">
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
            <?php foreach ($inProgress as $item): ?>
                <tr>
                    <td>
                        <?= \common\models\Order::getStatusTextFromStatus($item['status']) ?>
                    </td>
                    <td>
                        <?= $item['address'] ?>
                        <?php if ($item['address_2']): ?>
                            <?= $item['address_2'] ?>
                        <?php endif; ?>
                        /
                        <b><?= $item['zip'] ?></b>
                    </td>
                    <td>
                        <?= $item['customer']['username'] ?>
                        <br>
                        <?= $item['customer']['phone_number'] ?>
                    </td>
                    <td>
                        <?php $orderTotal = 0; ?>
                        <?php foreach ($item['orderItems'] as $key => $gift): ?>
                            <?= $gift['description'] ?> - <?= $gift['item_price'] ?> <b>x<?= $gift['count'] ?></b><br>
                            <?php $orderTotal += $gift['total_price']; ?>
                        <?php endforeach; ?>

                        Order Total - <?= $orderTotal ?>
                    </td>
                    <td>
                        <a href="?complete=<?= $item['id'] ?>">
                            <div class="btn btn-default btn-sm" style="cursor: pointer">Complete</div>
                        </a>
                        <a href="?cancelSupplier=<?= $item['id'] ?>">
                            <div class="btn btn-default btn-sm" style="cursor: pointer">Cancel by deliver</div>
                        </a>
                        <a href="?cancelDeliver=<?= $item['id'] ?>">
                            <div class="btn btn-default btn-sm" style="cursor: pointer">Cancel by supplier</div>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>











    </div>



    <?php \yii\widgets\Pjax::begin(['id' => 'supplier_table_1',
        'options' => [
            'class' => '',
            'tag' => 'div'
        ]]); ?>


    <script>
        setTimeout(function () {
            if (typeof $.pjax !== 'undefined') {
                $.pjax.reload({container: "#supplier_table_1"});
            }
        }, 5000);
    </script>
    <?php \yii\widgets\Pjax::end(); ?>

</section>

