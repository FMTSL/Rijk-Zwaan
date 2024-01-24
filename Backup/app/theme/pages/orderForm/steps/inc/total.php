<div class="rounded bg-white">
  <div class="card-body">
    <div class="row">
      <div class="col-md-4 ">Gross value:
        R$ <?= number_format($totalNot->value_not_discount, 2, ',', '.'); ?>
      </div>
      <div class="col-md-4">Net value:
        R$ <?= number_format($total->value_icms, 2, ',', '.'); ?>
      </div>
      <div class="col-md-4">
        <?php if ($commercialOrderAllcounts == 0 && $totalSalanova == 0 && $aditionalDiscountOrderAllcounts == 0 && $totalBonusOrder > 0) : ?>
        <a class="btn btn-success btn-block btn-lg"
          href="<?= url("order-to-order/step-tree"); ?>/<?= $client->id; ?>/<?= $order->id; ?>">Finish</a>
        <?php else : ?>

        <?php if ($client->special_client == 1) : ?>
        <?php if ($total->value_icms > 1) : ?>
        <a class="btn btn-success btn-block btn-lg"
          href="<?= url("order-to-order/step-tree"); ?>/<?= $client->id; ?>/<?= $order->id; ?>">Finish</a>
        <?php else : ?>
        <cite> Special customer, start your order</cite>
        <?php endif; ?>

        <?php else : ?>
        <?php if ($total->value_icms > 2500) : ?>
        <a class="btn btn-success btn-block btn-lg"
          href="<?= url("order-to-order/step-tree"); ?>/<?= $client->id; ?>/<?= $order->id; ?>">Finish</a>
        <?php else : ?>
        <cite> Minimum order over R$ 2500.00</cite>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>