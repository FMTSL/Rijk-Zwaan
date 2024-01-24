<?php $v->layout("template/theme");
include __DIR__ . "/../../template/sidebar.php"; ?>
<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
          <h3 class="text-3xl"><?= $title; ?> #<?= $order->order_number; ?></h3>
          <p class="text-subtitle text-muted"><?= $description; ?></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= url(); ?>">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <div class="page-content">

    <section class="section">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="id_customer">Client</label>
                <input type="text" id="" name="" class="form-control" value="<?= $client->full_name; ?>" disabled>
              </div>

              <div class="form-group">
                <label for="id_category">Category</label>
                <input type="text" id="" name="" class="form-control" value="<?= $customerCategory->name; ?>" disabled>
              </div>
            </div>
            <div class="col-md-6">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="helperText">Additional discount</label>
                    <input type="text" id="additional_discount" name="additional_discount" class="form-control"
                      value="<?= $order->additional_discount; ?>%" disabled>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="request_number">Payment Type</label>
                    <?php foreach ($customerPaymentType as $pagType) : ?>
                    <?php if ($pagType->id == $order->payment_type) : ?>
                    <input type="text" id="payment_type" name="payment_type" class="form-control"
                      value="<?= $pagType->name; ?>" disabled>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4" <?php if ($order->cash_payment == 0) : ?>style="display: none;" <?php endif; ?>>
                  <div class="form-group position-relative cash_payment">
                    <label for="helperText">Cash payment</label>
                    <input type="text" id="cash_payment" name="cash_payment" class="form-control"
                      value="<?= $order->cash_payment; ?>%" disabled>
                  </div>
                </div>

                <div <?php if ($order->cash_payment == 0) : ?>class="col-md-8" <?php else : ?> class="col-md-4"
                  <?php endif; ?>>
                  <div class="form-group">
                    <label for="id_category">Payment conditions</label>
                    <?php foreach ($customerCreditDeadline as $pagTerm) : ?>
                    <?php if ($pagTerm->id == $order->id_payment_term) : ?>
                    <input type="text" id="id_payment_term" name="id_payment_term" class="form-control"
                      value="<?= $pagTerm->deadline; ?>" disabled>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="bonus_order">Bonus order</label>
                    <?php if ($order->bonus_order == 1) : ?>
                    <input type="text" id="bonus_order" name="bonus_order" class="form-control" value="YES" disabled>
                    <?php else : ?>
                    <input type="text" id="bonus_order" name="bonus_order" class="form-control" value="NO" disabled>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <div class="form-group bonus" <?php if ($order->bonus_order == 0) : ?> style="display: none;"
                <?php endif; ?>>
                <label for="bonus">Bonus</label>
                <?php foreach ($bonusOrder as $bonus) : ?>
                <?php if ($bonus->id == $order->id_bonus_order) : ?>
                <input type="text" id="bonus" name="bonus" class="form-control" value="<?= $bonus->name; ?>" disabled>
                <input type="hidden" id="bonus_discount" name="bonus_discount" class="form-control"
                  value="<?= $bonus->discount; ?>">
                <?php endif; ?>
                <?php endforeach; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>


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
                    <th class="">Cash Discount</th>
                    <th class="">Price per MX</th>
                    <th class="">ICMS</th>
                    <th class="">Price per MX After Discount + ICMS</th>
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
                    <td class="text-bold-500"><?= $item->quantity; ?></td>
                    <td class="text-bold-500">
                      <?= $item->category_discount  +  $item->volume_condition ?>%
                    </td>
                    <td class="text-bold-500">
                      <?php if ($order->cash_payment) : ?>
                      <?= $order->cash_payment; ?>%
                      <?php else : ?>0%<?php endif; ?>
                    </td>
                    <td class="text-bold-500">R$ <?= $item->price; ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
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
                    <td><strong>Gross value: R$ <?= $commercialOrderAllValueNotDiscount->value_not_discount; ?></strong>
                    </td>
                    <td><strong>Net value: R$ <?= $commercialOrderAllValue->value_icms; ?></strong></td>
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
                    <th class="">Variety</th>

                    <th class="">Packaging</th>
                    <th class="">Quantity in MX</th>
                    <th class="">Discount</th>
                    <th class="">Cash Discount</th>
                    <th class="">Price per MX</th>
                    <th class="">ICMS</th>
                    <th class="">Price per MX After Discount + ICMS</th>
                    <th class="">Net value</th>

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
                    <td class="text-bold-500"><?= $item->quantity; ?></td>
                    <td class="text-bold-500">
                      <?= $item->bonus_order ?>%
                    </td>
                    <td class="text-bold-500">R$ <?= $item->price; ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
                    </td>
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
                    <td><strong>Gross value: R$ <?= $bonnusOrderAllValueNotDiscount->value_not_discount; ?></strong>
                    </td>
                    <td><strong>Net value: R$ <?= $bonnusOrderAllValue->value_icms; ?></strong></td>
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
                    <th class="">ICMS</th>
                    <th class="">Price per MX After Discount + ICMS</th>
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
                    <td class="text-bold-500"><?= $item->quantity; ?></td>
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
                    <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
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
                    <td><strong>Gross value: R$ <?= $salanovaOrderAllValueNotDiscount->value_not_discount; ?></strong>
                    </td>
                    <td><strong>Net value: R$ <?= $salanovaOrderAllValue->value_icms; ?></strong></td>
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
                    <th class="">Cash Discount</th>

                    <th class="">Price per MX</th>
                    <th class="">ICMS</th>
                    <th class="">Price per MX After Discount + ICMS</th>
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
                    <td class="text-bold-500"><?= $item->quantity; ?></td>
                    <td class="text-bold-500">
                      <?php
                                                                                    switch ($item->aditional_discount) {
                                                                                      case 1:
                                                                                        $new_category_discount = ($item->category_discount - 15) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 2:
                                                                                        $new_category_discount = ($item->category_discount - 10) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 3:
                                                                                        $new_category_discount = ($item->category_discount - 5) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 4:
                                                                                        $new_category_discount = ($item->category_discount  + 1) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 5:
                                                                                        $new_category_discount = ($item->category_discount + 2) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 6:
                                                                                        $new_category_discount = ($item->category_discount + 3) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 7:
                                                                                        $new_category_discount = ($item->category_discount + 4) +  $item->volume_condition;
                                                                                        break;
                                                                                      case 8:
                                                                                        $new_category_discount = ($item->category_discount + 5) +  $item->volume_condition;
                                                                                        break;
                                                                                      default:
                                                                                        $new_category_discount = ($item->category_discount + 0) +  $item->volume_condition;
                                                                                        break;
                                                                                    }
                                                                                    echo $new_category_discount ?>%
                    </td>
                    <td class="text-bold-500">
                      <?php if ($order->cash_payment) : ?>
                      <?= $order->cash_payment; ?>%
                      <?php else : ?>0%<?php endif; ?>
                    </td>
                    <td class="text-bold-500">R$ <?= $item->price; ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
                    <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value, 2, ',', '.'); ?></td>

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
                        <?= $aditionalDiscountOrderAllValueNotDiscount->value_not_discount; ?></strong></td>
                    <td><strong>Net value: R$ <?= $aditionalDiscountOrderAllValue->value_icms; ?></strong></td>
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
  </section>
  <section class="section">
    <div class="card">
      <div class="card-body">
        <form method="post" id="form" action="<?= url("order-to-order/form/new-finish"); ?>">
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
                    <?php foreach ($address as $da) : ?>
                    <?php if ($da->id == $order->delivery_address) : ?>
                    <p><?= $da->address_1; ?> <?= $da->address_2; ?> - <?= $da->city; ?>

                      <?php foreach ($state as $st) : ?>
                      <?php if ($da->id_state == $st->id) : ?>
                      <?= $st->name; ?>
                      <?php endif; ?>
                      <?php endforeach; ?>

                      <?php foreach ($country as $ct) : ?>
                      <?php if ($da->id_country == $ct->id) : ?>
                      <?= $ct->name; ?>
                      <?php endif; ?>
                      <?php endforeach; ?></p>
                    <?php endif; ?>
                    <?php endforeach; ?>

                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="disabledInput">Delivery date</label>
                    <p><?= $order->order_date; ?></p>
                  </div>
                </div>

              </div>


            </div>
          </div>



        </form>
      </div>
    </div>
  </section>



  <section class="section">
    <div class="card">
      <div class="card-body">

        <table style="width: 100%">
          <tr>
            <td align="right">
              <table class="col-md-5">
                <tr>
                  <td>
                    Gross value:
                  </td>
                  <td align="right">
                    <div id="subtotal_value">
                      <?php if ($order->bonus_order == 1) : ?>
                      --
                      <?php else : ?>
                      R$ <?= number_format($order->value_total, 2, ',', '.'); ?>
                      <?php endif; ?>

                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    Discount:
                  </td>
                  <td align="right">
                    <div id="discount_value">
                      <?php if ($order->bonus_order == 1) : ?>
                      --
                      <?php else : ?>
                      - R$
                      <?php $valor = $order->value_total;
                        $porcentagem = $order->discount + $order->additional_discount + $order->cash_payment;
                        $resultado = $valor * ($porcentagem / 100); ?><?= number_format($resultado, 2, ',', '.'); ?>
                    </div>
                    <?php endif; ?>



                  </td>
                </tr>



                <tr id="freight">
                  <td>
                    Freight:
                  </td>
                  <td align="right">
                    <?php if ($order->bonus_order == 1) : ?>
                    --
                    <?php else : ?>
                    <div id="freight_value">R$ <?= $order->freight; ?></div>
                    <?php endif; ?>


                  </td>
                </tr>

                <tr>
                  <td>
                    ICMS:
                  </td>
                  <td align="right">
                    <?php if ($order->bonus_order == 1) : ?>
                    --
                    <?php else : ?>
                    <div id="icms_value">
                      <?php $valor = $order->value_total;
                        $porcentagem = $tax->tax;
                        $resultado = $valor * ($porcentagem / 100); ?>
                      R$ <?= number_format($resultado, 2, ',', '.'); ?></div>
                    <?php endif; ?>


                  </td>
                </tr>

                <!-- <tr>
                                    <td class="pb-3">
                                        Total order:
                                    </td>
                                    <td align="right">
                                        <div id="total_order_value"></div>
                                    </td>
                                </tr> -->

                <tr class="border-top">
                  <td class="pt-3">
                    <strong>Total:</strong>
                  </td>
                  <td>
                    <div id="total_value" class="size20 text-right">
                      <?php if ($order->bonus_order == 1) : ?>
                      --
                      <?php else : ?>
                      <div id="icms_value">
                        R$ <?= number_format($order->value + $tax->tax + $order->freight, 2, ',', '.'); ?>
                      </div>
                      <?php endif; ?>


                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </section>

</div>
</div>
<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet" media="screen">
<link href="<?= url("theme/assets/css/select2.min.css"); ?>" rel="stylesheet" media="screen" />
<?php $v->end(); ?>
<?php $v->start("modal"); ?>
<div class="modal-body">
  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
    style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px"
    viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
    <path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none">
      <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1"
        values="0 50 51;360 50 51"></animateTransform>
    </path>
  </svg>
  <h3 class="swal2-title text-center mensagemID" id="mensagem">Aguarde estamos processando as informações!</h3>
  <div id="duomensagem"></div>
</div>
<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/bootstrap-datetimepicker.js"); ?>" charset="UTF-8">
</script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>"
  charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/select2.min.js"); ?>"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/orderForm02.js"); ?>"></script>
<?php $v->end(); ?>