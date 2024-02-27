<div class="" id="table-striped">
  <div class="card-content">
    <div class="card-body">
      <div class="col-md-12 rounded bg-greenYellow mb-4">
        <form method="post" id="aditionalDiscount" action="<?= url("order-to-order/carrinho/new-six"); ?>" class="p-2 bg-cian">
          <input type="hidden" id="cliente_id_aditional_discount" name="cliente_id_aditional_discount" value="<?= $client->id; ?>">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="variety_aditional_discount" class="w-full">Variety</label>
                <select class="form-select selectSearch w-full" id="variety_aditional_discount" name="variety_aditional_discount">
                  <option value=0">Select</option>
                  <?php
                  sort($variety);
                  foreach ($variety as $var) : ?>
                    <option value="<?= $var->id; ?>"><?= $var->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="id_stock_aditional_discount">Packaging</label>
                <select class="form-select" id="id_stock_aditional_discount" name="id_stock_aditional_discount" disabled required>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="quantity_aditional_discount">Quantity in MX</label>
                <input type="text" id="quantity_aditional_discount" name="quantity_aditional_discount" class="form-control" disabled required>
              </div>
            </div>

            <div id="step2" class="col-md-2">
              <div class="form-group position-relative">
                <label for="volume_condition_aditional_discount">Volume condition</label>
                <select class="form-select" id="volume_condition_aditional_discount" name="volume_condition_aditional_discount" disabled>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group position-relative">
                <label for="aditional_discount_aditional_discount">Aditional discount</label>
                <select class="form-select" id="aditional_discount_aditional_discount" name="aditional_discount_aditional_discount" disabled required>
                  <option value="">Select</option>
                  <option value="1">-15%</option>
                  <option value="2">-10%</option>
                  <option value="3">-5%</option>
                  <option value="4">1%</option>
                  <option value="5">2%</option>
                  <option value="6">3%</option>
                  <option value="7">4%</option>
                  <option value="8">5%</option>
                </select>
              </div>
            </div>
            <div class="col-md-1">
              <button class="btn btn-success float-left mt-4" id="btn_prod"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <input type="hidden" id="id_order_aditional_discount" name="id_order_aditional_discount" value="<?= $order->id; ?>">
          <input type="hidden" id="client_id_aditional_discount" name="client_id_aditional_discount" value="<?= $order->id_customer; ?>">
        </form>
      </div>
      <div id="productList_aditional_discount">
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
                              case 9:
                                $new_category_discount = ($item->category_discount - 1);
                                break;
                                case 10:
                                $new_category_discount = ($item->category_discount - 2);
                                break;
                                case 11:
                                $new_category_discount = ($item->category_discount - 3);
                                break;
                                case 12:
                                $new_category_discount = ($item->category_discount - 4);
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
                        <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
                        </td>
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
                      <?= number_format($aditionalDiscountOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php else : ?>
          <div class="alert alert-secondary text-center">No products for this Aditional Discount!</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
</div>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/steps/stepiSixUpdate.js"); ?>"></script>