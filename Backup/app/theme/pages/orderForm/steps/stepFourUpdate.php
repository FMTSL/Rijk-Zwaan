<div class="" id="table-striped">
  <div class="card-content">
    <div class="card-body">
      <div class="col-md-12 rounded bg-greenYellow mb-4">
        <form method="post" id="salanova" action="<?= url("order-to-order/carrinho/new-four"); ?>" class="p-2 bg-cian">
          <input type="hidden" id="cliente_id_sala_nova" name="cliente_id_sala_nova" value="<?= $client->id; ?>">
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label for="variety_sala_nova" class="w-full">Variety</label>
                <select class="form-select selectSearch w-full" id="variety_sala_nova" name="variety_sala_nova">
                  <option value=0">Select</option>
                  <option value="49">BARLACH</option>
                  <option value="26">BELLAGON</option>
                  <option value="50">COUSTEAU</option>
                  <option value="27">DESIRADE</option>
                  <option value="51">EULER</option>
                  <option value="28">EXCENTRIC</option>
                  <option value="52">KLEE</option>
                  <option value="53">SARTRE</option>
                  <option value="29">TRIPLEX</option>
                  <option value="54">XANDRA</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="id_stock_sala_nova">Packaging</label>
                <select class="form-select" id="id_stock_sala_nova" name="id_stock_sala_nova" disabled>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="quantity_sala_nova">Quantity in MX</label>
                <input type="text" id="quantity_sala_nova" name="quantity_sala_nova" class="form-control" disabled>
              </div>
            </div>




            <div class="col-md-2">
              <div class="form-group position-relative">
                <label for="sala_nova_sala_nova">Discount Salanova</label>
                <select class="form-select" id="sala_nova_sala_nova" name="sala_nova_sala_nova" disabled>
                  <?php foreach ($salanova as $sal) : ?>
                  <option value="<?= $sal->discount; ?>"><?= $sal->discount; ?>%</option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-1">
              <button class="btn btn-success float-left mt-4" id="btn_prod"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <input type="hidden" id="id_order_sala_nova" name="id_order_sala_nova" value="<?= $order->id; ?>">
          <input type="hidden" id="client_id_sala_nova" name="client_id_sala_nova" value="<?= $order->id_customer; ?>">
        </form>
      </div>
      <div id="productList_sala_nova">
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

                <td><strong>Gross value: R$
                    <?= number_format($salanovaOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong>
                </td>
                <td><strong>Net value: R$
                    <?= number_format($salanovaOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
                </td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else : ?>
        <div class="alert alert-secondary text-center">No products for this Salanova!</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
</div>

<script type="text/javascript" src="<?= url("theme/assets/js/pages/steps/stepiFourUpdate.js"); ?>"></script>