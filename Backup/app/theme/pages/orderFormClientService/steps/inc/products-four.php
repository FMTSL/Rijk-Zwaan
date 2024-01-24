<?php if ($itens) : ?>
  <div class="table-responsive-w-100">
    <table id="tabela" class="table table-striped mb-0">
      <thead>
        <tr>
          <th class="">Variety</th>
          <th class="">Packaging</th>
          <th class="">Quantity in MX</th>
          <th class="">Discount</th>
          <th class="">Cash Discount</th>
          <th class="">Price per MX</th>
          <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after Discount without ICMS</th>
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
                  <?= $item->salanova ?>%
                </td>
                <td class="text-bold-500">
                  <?php if ($order->cash_payment) : ?>
                    <?= $order->cash_payment; ?>%
                    <?php else : ?>0%<?php endif; ?>
                </td>
                <td class="text-bold-500">R$ <?= $item->price; ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?></td>
                <td class="text-bold-500"><button id-prod="<?= $item->id; ?>" class="delet btn btn-danger"><i class="fa fa-times"></i></button></td>
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
          <td><strong>Gross value: R$
              <?= number_format($salanovaOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong></td>
          <td><strong>Net value: R$ <?= number_format($salanovaOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
<?php else : ?>
  <div class="alert alert-secondary text-center">No products for this Salanova!</div>
<?php endif; ?>
<script>
  function carregaProdutos() {
    const id = $("#id_order_sala_nova").val();
    const clientid = $("#client_id_sala_nova").val();

    $("#stepFourUpdate #productList_sala_nova, #valorTotal").html("");
    $.get(
      `/order-to-order-list/${id}/${clientid}/step-four-products`,
      function(dd) {
        $("#stepFourUpdate #productList_sala_nova").html(dd);
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