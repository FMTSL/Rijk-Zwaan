<section class="section p-0 mb-6 bg-white rounded">
  <div class="accordion-container rounded">
    <div class="set stepTwoUpdate rouded">
      <a href="#" class="active">
        <i class="fa fa-list-ol"></i>
        Commercial Order
        <div class="float-right"><i class="fa fa-chevron-down"></i></div>
      </a>
      <div id="stepTwoUpdate" class="content" style="display: block;">
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
                  <?= $item->category_discount  ?>%
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
                    <?= number_format($commercialOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>

              </tr>
            </tfoot>
          </table>
        </div>
        <?php else : ?>
        <div class="alert alert-secondary text-center">No products for this order!</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="set stepTreeUpdate">
      <a href="#" class="active">
        <i class="fa fa-plus"></i>
        Bonus order
        <div class="float-right"><i class="fa fa-chevron-down"></i></div>
      </a>
      <div id="stepTreeUpdate" class="content" style="display: block;"><?php if ($itensBonnus) : ?>
        <div class="table-responsive-w-100">
          <table id="tabela" class="table table-striped mb-0">
            <thead>
              <tr>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Variety</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Packaging</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Bonus Type</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Quantity in MX</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Discount</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Cash Discount</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX</th>

                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after
                  discount with ICMS</th>
                <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Net value</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($itensBonnus as $item) : ?>
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
                  <?php foreach ($bonusOrder as $bonus) : ?> <?php if ($bonus->id == $item->bonus_type) : ?>
                  <?= $bonus->name; ?> <?php endif; ?> <?php endforeach; ?></td>
                </td>
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
                <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
                </td>
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
                    <?= number_format($bonnusOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong>
                </td>
                <td><strong>Net value: R$ <?= number_format($bonnusOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
                </td>

              </tr>
            </tfoot>
          </table>
        </div>
        <?php else : ?>
        <div class="alert alert-secondary text-center">No products for this order!</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="set stepFourUpdate">
      <a href="#" class="active">
        <i class="fa fa-plus"></i>
        Salanova
        <div class="float-right"><i class="fa fa-chevron-down"></i></div>
      </a>
      <div id="stepFourUpdate" class="content" style="display: block;"><?php if ($itensSalanova) : ?>
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

              </tr>
            </thead>
            <tbody>
              <?php foreach ($itensSalanova as $item) : ?>
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

              </tr>
            </tfoot>
          </table>
        </div>
        <?php else : ?>
        <div class="alert alert-secondary text-center">No products for this order!</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="set stepSixUpdate">
      <a href="#" class="active">
        <i class="fa fa-plus"></i>
        Aditional Discount
        <div class="float-right"><i class="fa fa-chevron-down"></i></div>
      </a>
      <div id="stepFourUpdate" class="content" style="display: block;"><?php if ($itensAditionalDiscount) : ?>
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
              </tr>
            </thead>
            <tbody>
              <?php foreach ($itensAditionalDiscount as $item) : ?>
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
                <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
                </td>

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
                    <?= number_format($aditionalDiscountOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong>
                </td>
                <td><strong>Net value: R$
                    <?= number_format($aditionalDiscountOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>

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
</section>

<section class="section">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="delivery">Comments</label>
                <p><?= $order->comments; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="card">
    <div class="card-body">

      <div class="row">
        <div class="col-md-12">
          <div class="row">

            <div class="col-md-3">
              <div class="form-group">
                <label for="delivery">Delivery type</label>

                <?php foreach ($deliveryType as $dt) : ?>
                <?php if ($dt->id == $order->delivery) : ?>
                <p><?= $dt->name; ?></p>
                <?php endif; ?>
                <?php endforeach; ?>

              </div>

            </div>
            <div class="col-md-5 <?php if ($order->delivery == 3) : ?>hide<?php endif; ?>">
              <div class="form-group">
                <label for="delivery_address">Delivery address</label>

                <p><?= $address->address_1; ?> <?= $address->address_2; ?> - <?= $address->city; ?>

                  <?php foreach ($state as $st) : ?>
                  <?php if ($address->id_state == $st->id) : ?>
                  <?= $st->name; ?>
                  <?php endif; ?>
                  <?php endforeach; ?>

                  <?php foreach ($country as $ct) : ?>
                  <?php if ($address->id_country == $ct->id) : ?>
                  <?= $ct->name; ?>
                  <?php endif; ?>
                  <?php endforeach; ?> <br /> Zip Code: <?= $address->zipcode; ?>

                </p>

              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="disabledInput">Delivery date</label>
                <p>
                  <?= date('d/m/Y', strtotime($order->order_date)); ?></p>
              </div>
            </div>

          </div>


        </div>
      </div>



    </div>
  </div>
</section>