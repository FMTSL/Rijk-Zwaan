<div class="" id="table-striped">
  <div class="card-content">
    <div class="card-body">
      <div class="col-md-12 rounded bg-greenYellow mb-4">
        <form method="post" id="commercialOrder" action="<?= url("order-to-order/carrinho/new"); ?>"
          class="p-2 bg-cian">
          <input type="hidden" id="cliente_id" name="cliente_id" value="<?= $client->id; ?>">
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label for="variety" class="w-full">Variety</label>
                <select class="form-select selectSearch w-full" id="variety" name="variety">
                  <option value=0">Select</option>
                  <?php
                  sort($variety);
                  foreach ($variety as $var) : ?>
                  <option value="<?= $var->id; ?>"><?= $var->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="id_stock">Packaging</label>
                <select class="form-select" id="id_stock" name="id_stock" disabled>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="quantity">Quantity in MX</label>
                <input type="text" id="quantity" name="quantity" class="form-control" disabled>
              </div>
            </div>

            <div id="step2" class="col-md-2">
              <div class="form-group position-relative">
                <label for="volume_condition">Volume condition</label>
                <select class="form-select" id="volume_condition" name="volume_condition" disabled>
                </select>
              </div>
            </div>

            <div class="col-md-1">
              <button class="btn btn-success float-left mt-4" id="btn_prod"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <input type="hidden" id="id_order" name="id_order" value="<?= $order->id; ?>">
          <input type="hidden" id="client_id" name="client_id" value="<?= $order->id_customer; ?>">
        </form>
      </div>
      <div id="productList">
        <?php if ($itens) : ?>
        <div class="table-responsive-w-100">
          <table id="tabela" class="table table-striped mb-0">
            <thead>
              <tr>
                <th class="">Variety</th>
                <th class="">Packaging</th>
                <th class="">Quantity in MX</th>
                <th class="">Discount</th>
                <th class="">Volume condition</th>
                <th class="">Cash Discount</th>
                <th class="">Price per MX</th>

                <th class="">Price per MX after discount with ICMS</th>
                <th class="">Net value</th>
                <th class=""></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($itens as $item) : ?>
              <?php foreach ($products as $product) : ?>
              <?php if ($item->id_stock == $product->id) : ?>
              <tr>
                <td class="text-bold-500">
                  <?php foreach ($variety as $var) : ?>
                  <?php if ($var->id == $product->id_variety) : ?>
                  <?= $var->name; ?>
                  <?php endif; ?>
                  <?php endforeach; ?>
                </td>
                <td class="text-bold-500">
                  <?php foreach ($stock as $stoc) : ?>
                  <?php if ($item->id_stock == $stoc->id) : ?>
                  <?php foreach ($package as $pack) : ?>
                  <?php if ($pack->id == $stoc->id_package) : ?>
                  <?= $pack->name; ?>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  <?php endif; ?>
                  <?php endforeach; ?>
                </td>
                <td class="text-bold-500"><?= number_format($item->quantity, 2, ',', '.'); ?></td>
                <td class="text-bold-500">
                  <?= $item->category_discount ?>%
                </td>
                <td class="text-bold-500">
                  <?= $item->volume_condition + 0 ?>%
                </td>
                <td class="text-bold-500">
                  <?php if ($order->cash_payment) : ?>
                  <?= $order->cash_payment; ?>%
                  <?php else : ?>0%<?php endif; ?>
                </td>
                <td class="text-bold-500">R$ <?= $item->price; ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
                </td>
                <td class="text-bold-500"><button onclick="deletar(<?= $item->id; ?>)" class="delet btn btn-danger"><i
                      class="fa fa-times"></i></button></td>
              </tr>
              <?php endif; ?>
              <?php endforeach; ?>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Gross value: R$
                    <?= number_format($commercialOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong>
                </td>
                <td><strong>Net value: R$
                    <?= number_format($commercialOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
                </td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else : ?>
        <div class="alert alert-secondary text-center">No products for this order!</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
</div>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/steps/stepiTwoUpdate.js"); ?>"></script>