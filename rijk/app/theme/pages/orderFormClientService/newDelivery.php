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
    <section class="section mb-4">
      <div class="bg-white">
        <div class="card-body ">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label for="id_customer">Customer</label>
                <input type="text" id="" name="" class="form-control" value="<?= $client->full_name; ?>" disabled>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="id_category">Category</label>
                <input type="text" id="<?= $customerCategory->id; ?>" name="" class="form-control" value="<?= $customerCategory->name; ?>" disabled>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="<?php if ($order->payment_type == 1) : ?> col-md-7 <?php else : ?> col-md-12 <?php endif; ?> cash_payment_type">
                  <div class="form-group">
                    <label for="request_number">Payment Type</label>
                    <select class="form-select" id="payment_type" name="payment_type" disabled>
                      <option value="0">Select</option>
                      <?php foreach ($customerPaymentType as $pagTerm) : ?>
                        <option value="<?= $pagTerm->id; ?>" <?php if ($pagTerm->id == $order->payment_type) : ?>selected<?php endif; ?>>
                          <?= $pagTerm->name; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="form-group position-relative cash_payment col-md-5" <?php if ($order->payment_type == 1) : ?> style="display: block;" <?php else : ?> style="display: none;" <?php endif; ?>>
                  <label for="helperText">Cash payment</label>
                  <select class="form-select" id="cash_payment" name="cash_payment" disabled>
                    <option value="0">Select</option>
                    <option value="1" <?php if ($order->cash_payment == 1) : ?>selected <?php endif; ?>>1%</option>
                    <option value="2" <?php if ($order->cash_payment == 2) : ?> selected<?php endif; ?>>2%</option>
                    <option value="3" <?php if ($order->cash_payment == 3) : ?> selected <?php endif; ?>>3%</option>
                  </select>
                </div>


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
                      <th class="">Volume condition</th>
                      <th class="">Cash Discount</th>
                      <<th class="">Price per MX</th>
                        <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after Discount without ICMS</th>
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
                            <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
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
                      <th class="">Variety</th>
                      <th class="">Packaging</th>
                      <th class="">Bonus Type</th>
                      <th class="">Quantity in MX</th>
                      <th class="">Discount</th>
                      <th class="">Cash Discount</th>
                      <th class="">Price per MX</th>
                      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after Discount without ICMS</th>
                      <th class="">Price per MX after discount with ICMS</th>
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
                            <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
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
                      <td></td>
                      <td><strong>Gross value: R$
                          <?= number_format($bonnusOrderAllValueNotDiscount->value_not_discount, 2, ',', '.'); ?></strong>
                      </td>
                      <td><strong>Net value: R$
                          <?= number_format($bonnusOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
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
                      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after Discount without ICMS</th>
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
                            <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
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
                      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after Discount without ICMS</th>
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
                            <td class="text-bold-500">R$ <?= number_format($item->value, 2, ',', '.'); ?></td>
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
  <section class="section mt-4">
    <div class="bg-white">
      <div class="card-body">
        <form method="post" id="form" action="<?= url("order-to-order/form/new-finish"); ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="row">

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="id_payment_term">Payment conditions <span style="color: red;">*</span></label>
                    <select class="form-select" id="id_payment_term" name="id_payment_term" required>
                      <option value="0">Select</option>
                      <?php foreach ($tipoPagamento as $tipPag) : ?>
                        <?php if ($order->payment_type == $tipPag->type_payment && $tipPag->id_customer_category == $customerCategory->id) : ?>
                          <option value="<?= $tipPag->id; ?>" <?php if ($order->id_payment_term == $tipPag->id) : ?>selected<?php endif; ?>>
                            <?= $tipPag->deadline; ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>


                  <div class="form-group">
                    <label for="delivery">Delivery type <span style="color: red;">*</span></label>
                    <select class="form-select" id="delivery" name="delivery" required <?php if (!$order->delivery) : ?>disabled<?php endif; ?>>
                      <option value="0">Select</option>
                      <?php foreach ($deliveryType as $dt) : ?>
                        <?php if ($dt->weight > $totalWeight->value) : ?>
                          <option value="<?= $dt->id; ?>" <?php if ($order->delivery == $dt->id) : ?>selected<?php endif; ?>><?= $dt->name; ?>
                          </option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group delivery_address_label">
                    <label for="delivery_address">Delivery address <span style="color: red;">*</span></label>
                    <select class="form-select" id="delivery_address" name="delivery_address" required <?php if (!$order->delivery_address) : ?>disabled<?php endif; ?>>
                      <option value="0">Select</option>

                      <?php foreach ($relationshipCustomerAddress as $addressRelations) : ?>
                        <?php foreach ($address as $da) : ?>
                          <?php if ($da->id == $addressRelations->id_address) : ?>
                            <option value="<?= $da->id; ?>" <?php if ($order->delivery_address == $da->id) : ?>selected<?php endif; ?>>
                              <?= $da->address_1; ?> <?= $da->address_2; ?> - <?= $da->city; ?>

                              <?php foreach ($state as $st) : ?>
                                <?php if ($da->id_state == $st->id) : ?>
                                  <?= $st->name; ?>
                                <?php endif; ?>
                              <?php endforeach; ?>

                              <?php foreach ($country as $ct) : ?>
                                <?php if ($da->id_country == $ct->id) : ?>
                                  <?= $ct->name; ?>
                                <?php endif; ?>
                              <?php endforeach; ?></option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="disabledInput">Delivery date <span style="color: red;">*</span></label>
                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                      <input class="form-control" size="16" type="text" value="<?php
                                                                                $date = date_create($order->order_date);
                                                                                echo date_format($date, "d/m/Y");
                                                                                ?>" id="order_date" name="order_date" readonly required>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input2" value="<?php
                                                                $date = date_create($order->order_date);
                                                                echo date_format($date, "Y/m/d");
                                                                ?>" name="delivery-date" /><br />
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" cols="20" rows="10"><?= $order->comments; ?></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" id="id" name="id" value="<?= $order->id; ?>">
          <input type="hidden" id="id_customer" name="id_customer" value="<?= $order->id_customer; ?>">
          <input type="hidden" id="special_client" name="special_client" value="<?= $client->special_client; ?>">

          <?php foreach ($relationshipCustomerAddress as $addressRelations) : ?>
            <?php foreach ($address as $key => $da) : ?>
              <?php if ($da->id == $addressRelations->id_address) : ?>
                <?php foreach ($state as $st) : ?>
                  <?php if ($da->id_state == $st->id) : ?>
                    <input type="hidden" id="id_customer_state" name="id_customer_state" value="<?= $da->id; ?>">
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>
          <input type="hidden" id="id_customer_category" name="id_customer_category" class="form-control" value="<?= $customerCategory->id; ?>">
          <input type="hidden" id="orderNumber" name="orderNumber" value="<?= $orderNumber; ?>">
          <input type="hidden" id="totalParc" name="totalParc" value="<?= $total->value_icms; ?>">
          <input type="hidden" id="totalWeight" name="totalWeight" value="<?= $totalWeight->value; ?>">
          <input type="hidden" id="total_form" name="total_form" value="<?= $total->value_icms; ?>">
          <input type="hidden" id="discount_form" name="discount_form" value="<?= $order->discount; ?>">
          <input type="hidden" id="aditional_discount" name="aditional_discount" value="<?= $aditionalDiscount; ?>">
          <input type="hidden" id="gross_value" name="gross_value" value="<?= $totalNot->value_not_discount; ?>">
          <input type="hidden" id="net_value" name="net_value" value="<?= $total->value_icms; ?>">
          <input type="hidden" id="total_value_db" name="total_value_db" value="<?= $total->value_icms + $order->freight; ?>">

          <input type="hidden" id="actual_status" name="actual_status" value="<?= $order->status; ?>">
          <input type="hidden" id="tax_value" name="tax_value" value="">
          <input type="hidden" id="tax" name="tax" value="<?= $order->tax; ?>">
          <input type="hidden" id="freight" name="freight" value="<?= number_format($order->freight, 2, '.', ','); ?>">
          <?php foreach ($customerCreditDeadline as $pagTerm) : ?>
            <?php if ($pagTerm->id == $order->id_payment_term) : ?>
              <input type="hidden" id="id_payment_term" name="id_payment_term" value="<?= $pagTerm->id; ?>">
            <?php endif; ?>
          <?php endforeach; ?>
          <button type="submit" id="enviaForm" class="hide"></button>
        </form>
      </div>
    </div>
  </section>

  <section class="section price_table">
    <div class="card">
      <div class="card-body">
        <table style="width: 100%">
          <tr>
            <td align="right">
              <table class="col-md-5">

                <tr>
                  <td>
                    Net value with ICMS:
                  </td>
                  <td align="right">
                    <div id="subtotal_value">
                      R$ <?= number_format($total->value_icms, 2, ',', '.'); ?>
                    </div>
                  </td>
                </tr>
                <tr id="cash_payment_table">

                </tr>
                <tr id="freight">
                  <td>
                    Freight with ICMS:
                  </td>
                  <td align="right">
                    <div id="freight_value">R$ <?= number_format($order->freight, 2, ',', '.'); ?></div>
                  </td>
                </tr>


                <tr class="border-top">
                  <td class="pt-3">
                    <strong>Total:</strong>
                  </td>
                  <td>
                    <div class="size20 text-right">
                      <div id="total_value" class="size20 text-right">
                        R$ <?= number_format($total->value_icms + $order->freight, 2, ',', '.'); ?>
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

  <section class="section">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-2">
          </div>
          <div class="col-md-2"></div>
          <div class="col-md-4"> <a class="btn btn-success btn-block btn-lg" id="getOrder">Generate order</a>
            <div class="col-md-4 "></div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
</div>
<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet" media="screen">
<link href="<?= url("theme/assets/css/select2.min.css"); ?>" rel="stylesheet" media="screen" />
<link href="<?= url("theme/assets/css/steps.css"); ?>" rel="stylesheet" media="screen" />
<?php $v->end(); ?>
<?php $v->start("modal"); ?>
<div class="modal-body">
  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
    <path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none">
      <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform>
    </path>
  </svg>
  <h3 class="swal2-title text-center mensagemID" id="mensagem">Aguarde estamos processando as informações!</h3>
  <div id="duomensagem"></div>
</div>
<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/bootstrap-datetimepicker.js"); ?>" charset="UTF-8">
</script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/select2.min.js"); ?>"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/orderForm02.js"); ?>"></script>
<script>
  $(".set > a").on("click", function() {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(this).siblings(".content").slideUp(200);
      $(this)
        .siblings("i")
        .removeClass("fa-chevron-down")
        .addClass("fa-chevron-ups");
    } else {
      $(this)
        .siblings("i")
        .removeClass("fa-chevron-down")
        .addClass("fa-chevron-up");
      $(this)
        .find("i")
        .removeClass("fa-chevron-up")
        .addClass("fa-chevron-down");
      $(this).removeClass("active");
      $(this).addClass("active");
      $(this).siblings(".content").slideUp(200);
      $(this).siblings(".content").slideDown(200);
    }
  });
</script>
<?php $v->end(); ?>