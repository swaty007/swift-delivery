<div class="product__wrap clearfix">
    <?php foreach ($gifts as $item): ?>
    <div class="product__item">
        <p class="text text--bold text--blue product__item--title">
            <?= $item['name']; ?>
        </p>

        <?php foreach ($item['productOptions'] as $option): ?>
            <p class="text text--small text--blue-opacity product__item--desc">
                <span><?= $option['name']; ?></span><span>$<?= number_format($option['price'], 0); ?></span>
            </p>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>