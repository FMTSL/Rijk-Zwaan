<?php if ($itens) : ?>
<div class="table-responsive-w-100">
  <table id="tabela" class="table table-striped mb-0">
    <thead>
      <tr>
        <th class="">Variety</th>
        <th class="">Packaging</th>
        <th class="">Bonus Type</th>
        <th class="">Quantity in MX</th>
        <th class="">Discount</th>
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
        <td class="text-bold-500">
          <?php foreach ($bonusOrder as $bonus) : ?>
          <?php if ($bonus->id == $item->bonus_type) : ?>
          <?= $bonus->name; ?>
          <?php endif; ?>
          <?php endforeach; ?></td>

        <td class="text-bold-500"><?= number_format($item->quantity, 2, ',', '.'); ?></td>
        <td class="text-bold-500">
          <?= $item->bonus_order ?>%
        </td>
        <td class="text-bold-500">
          <?php if ($order->cash_payment) : ?>
          <?= $order->cash_payment; ?>%
          <?php else : ?>0%<?php endif; ?>
        </td>
        <td class="text-bold-500">R$ <?= $item->price; ?></td>
        <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
        <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?></td>
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
            <?= number_format($bonnusOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong></td>
        <td><strong>Net value: R$ <?= number_format($bonnusOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
</div>
<?php else : ?>
<div class="alert alert-secondary text-center">No products for this bonus order!</div>
<?php endif; ?>
<script>
function carregaProdutos() {
  const id = $("#id_order_bonus_order").val();
  const clientid = $("#client_id_bonus_order").val();
  $("#stepTreeUpdate #productList_bonus_order").html("");
  $.get(
    `/order-to-order-list/${id}/${clientid}/step-tree-products`,
    function(dd) {
      $("#stepTreeUpdate #productList_bonus_order").html(dd);
    }
  );
  $.get(
    `/order-to-order-total/${id}/${clientid}/total`,
    function(dd) {
      $("#valorTotal").html(dd);
    }
  );
}
</script>