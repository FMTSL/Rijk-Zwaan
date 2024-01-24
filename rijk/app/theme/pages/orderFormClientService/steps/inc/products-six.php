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
                  <?php
                  switch ($item->aditional_discount) {
                    case 1:
                      $new_category_discount = ($item->category_discount - 15);
                      break;
                    case 2:
                      $new_category_discount = ($item->category_discount - 10);
                      break;
                    case 3:
                      $new_category_discount = ($item->category_discount - 5);
                      break;
                    case 4:
                      $new_category_discount = ($item->category_discount  + 1);
                      break;
                    case 5:
                      $new_category_discount = ($item->category_discount + 2);
                      break;
                    case 6:
                      $new_category_discount = ($item->category_discount + 3);
                      break;
                    case 7:
                      $new_category_discount = ($item->category_discount + 4);
                      break;
                    case 8:
                      $new_category_discount = ($item->category_discount + 5);
                      break;
                    default:
                      $new_category_discount = ($item->category_discount + 0);
                      break;
                  }
                  echo $new_category_discount ?>%
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
                <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?></td>
                <td class="text-bold-500"><button onclick="deletar(<?= $item->id; ?>)" class="delet btn btn-danger"><i class="fa fa-times"></i></button></td>
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
          <td></td>
          <td><strong>Gross value: R$
              <?= number_format($aditionalDiscountOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong>
          </td>
          <td><strong>Net value: R$
              <?= number_format($aditionalDiscountOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
<?php else : ?>
  <div class="alert alert-secondary text-center">No products for this Aditional Discount!</div>
<?php endif; ?>
<script>
  function carregaProdutos() {
    const id = $("#id_order_aditional_discount").val();
    const clientid = $("#client_id_aditional_discount").val();

    $("#stepSixUpdate #productList_aditional_discount, #valorTotal").html("");
    $.get(
      `/order-to-order-list/${id}/${clientid}/step-six-products`,
      function(dd) {
        $("#stepSixUpdate #productList_aditional_discount").html(dd);
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