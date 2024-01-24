<style>
*,
body,
@page {
  font-family: "OpenSans";
}

body {
  margin: 0px;
  background: #eee;
  -webkit-text-size-adjust: 100%;
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  background-color: #f2f7ff;
  color: #607080;
  font-family: Nunito, "Open Sans";
  font-size: 14px;
  line-height: 20px;
  font-weight: 400;
  line-height: 1.5;

}

.invoice {
  padding: 0px;
}

.col-md-12 {
  width: 100%;
  padding: 20px 0;
}

.invoice h2 {
  margin-top: 0px;
  line-height: 0.8em;
}

.invoice .small {
  font-weight: 300;
}

.invoice hr {
  margin-top: 10px;
  border-color: #ddd;
}

.invoice .table tr.line {
  border-bottom: 1px solid #ccc;
}

.invoice .table td {
  border: none;
}

.invoice .identity {
  margin-top: 10px;
  font-size: 14px;
  font-weight: 300;
}

.invoice .identity strong {
  font-weight: 600;
}


.grid {
  position: relative;
  margin: 15px;
  background: #fff;
  color: #666666;
  border-radius: 2px;
  padding: 25px;

}

.invoice-title h2,
.invoice-title h3 {
  display: inline-block;
}

.table {
  font-size: 14px;
}

.table>tbody>tr>.no-line {
  border-top: none;
}

.table>thead>tr>.no-line {
  border-bottom: none;
}

.table>tbody>tr>.thick-line {
  border-top: 2px solid;
}
</style>


<div class="container">
  <div class="row">
    <!-- BEGIN INVOICE -->
    <div class="col-xs-12">
      <div class="grid invoice">
        <div class="grid-body">
          <div class="invoice-title">
            <div class="row">
              <div class="col-xs-12">
                <h2>invoice<br>
                  <span class="small">order #<?= $order->order_number; ?></span>
                </h2>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <table style="width: 100%">
              <tr>
                <td valign="top">
                  <table>
                    <tr>
                      <td class="FieldLabel">Client:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><?= $client->full_name; ?></div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">Category:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><?= $customerCategory->name; ?></div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">Additional discount:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><?= $order->additional_discount; ?>%</div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">Payment Type:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><?php foreach ($customerPaymentType as $pagType) : ?>
                          <?php if ($pagType->id == $order->payment_type) : ?>
                          <?= $pagType->name; ?>
                          <?php endif; ?>
                          <?php endforeach; ?></div>
                      </td>
                    </tr>

                    <?php if ($order->cash_payment) : ?>
                    <tr>
                      <td class="FieldLabel">Cash payment:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><?= $order->cash_payment; ?>%</div>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td class="FieldLabel">Payment conditions:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly">
                          <?php foreach ($customerCreditDeadline as $pagTerm) : ?>
                          <?php if ($pagTerm->id == $order->id_payment_term) : ?>
                          <?= $pagTerm->deadline; ?>
                          <?php endif; ?>
                          <?php endforeach; ?></div>
                      </td>
                    </tr>

                    <tr>
                      <td class="FieldLabel">Salesman:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><?= $salesman->name; ?> - <?= $salesman->phone; ?></div>
                      </td>
                    </tr>

                    <tr>
                      <td class="FieldLabel"><strong>Comments:</strong></td>
                      <td>
                        <div class="FieldPlaceholder DataOnly"><strong><?= $order->comments; ?></strong></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top">

                  <table style="float: right" class="RightAlignedInputs table">
                    <tr>
                      <td class="FieldLabel">Address:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly" style="float: right">
                          <?= $address->address_1; ?> <?= $address->address_2; ?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">City:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly" style="float: right">
                          <?= $address->city; ?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">Region:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly" style="float: right">
                          <?php foreach ($states as $state) : ?>
                          <?php if ($address->id_state == $state->id) : ?>
                          <?= $state->name; ?>
                          <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">Postal Code:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly" style="float: right">
                          <?= $address->zipcode; ?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="FieldLabel">Ship Country:</td>
                      <td>
                        <div class="FieldPlaceholder DataOnly" style="float: right">
                          <?php foreach ($countrys as $country) : ?>
                          <?php if ($address->id_country == $country->id) : ?>
                          <?= $country->name; ?>
                          <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">

                  <div class="col-md-12">
                    <h3 class="text-3xl">ORDER SUMMARY</h3>
                    <?php include __DIR__ . "/inc/orderTablePrint.php"; ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td valign="bottom">
                  <div class="col-md-12">

                    <table class="table table-striped">
                      <tr>
                        <td class="FieldLabel">Ship Name:</td>
                        <td>
                          <div class="FieldPlaceholder DataOnly"><?= $client->full_name; ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="FieldLabel">Delivery type:</td>
                        <td>
                          <div class="FieldPlaceholder DataOnly">
                            <?php foreach ($deliveryType as $dt) : ?>
                            <?php if ($dt->id == $order->delivery) : ?>
                            <?= $dt->name; ?>
                            <?php endif; ?>
                            <?php endforeach; ?>
                          </div>
                        </td>
                      </tr>
                      <?php if ($order->delivery == 3) : ?>
                      <tr>
                        <td class="FieldLabel"><strong>Pickup Address:</strong></td>
                        <td>
                          <div class="FieldPlaceholder DataOnly">
                            <strong>Rijk Zwaan Holambra, Centro</strong>
                          </div>
                        </td>
                      </tr>
                      <?php endif; ?>
                    </table>
                  </div>
                </td>
                </td>
                <td align="right">
                  <table style="float: right;">
                    <tr>
                      <td align="left">
                        Net value:
                      </td>
                      <td align="right">
                        <div id="subtotal_value">
                          R$ <?= number_format($totalNotICMS->value, 2, ',', '.'); ?>
                        </div>
                      </td>
                    </tr>
                    <tr id="cash_payment_table">

                    </tr>
                    <?php if ($order->delivery == 3) : ?>
                    <?php else : ?>
                    <tr id="freight">
                      <td>
                        Freight:
                      </td>
                      <td align="right">
                        <div id="freight_value">R$
                          <?= number_format($order->freight - $order->tax_value, 2, ',', '.'); ?></div>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr class="border-top">
                      <td class="pt-3">
                        <strong>Total:</strong>
                      </td>
                      <td>
                        <div class="size20 text-right">
                          <div id="total_value" class="size20 text-right">
                            R$
                            <?= number_format($totalNotICMS->value + ($order->freight - $order->tax_value), 2, ',', '.'); ?>
                          </div>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>


          </div>


          <!-- <div class="row">
                        <div class="col-md-12 text-right identity">
                            <p>Designer identity<br><strong>Alex Marques</strong></p>
                        </div>
                    </div> -->
        </div>
      </div>
    </div>
    <!-- END INVOICE -->
  </div>
</div>