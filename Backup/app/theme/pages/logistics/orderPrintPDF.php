<style>
*,
body,
@page {
  font-family: "OpenSans";
}

body {
  margin: 0px;
  -webkit-text-size-adjust: 100%;
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  color: #607080;
  font-family: Nunito, "Open Sans";
  font-size: 14px;
  font-weight: 400;

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
  background: #fff;
  color: #666666;
  border-radius: 2px;
  padding: 0px;

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



<!-- BEGIN INVOICE -->
<div class="col-xs-12">
  <div class="grid invoice">
    <div class="grid-body">
      <div class="invoice-title">
        <h2 style="height:auto; padding:30px 0 10px 0;">INVOICE ORDER #<?= $order->order_number; ?></h2>
        <img src="https://rijkzwaanbrasil.com.br/theme/assets/images/logo/logo.png" width="80px" style="float:right;">
      </div>
      <hr>
      <div class="row">
        <table style="width: 100%">
          <tr>
            <td>
              <strong>Client:</strong> <?= $client->full_name; ?>
            </td>
            <td>
              <strong>Payment Type:</strong> <?php foreach ($customerPaymentType as $pagType) : ?>
              <?php if ($pagType->id == $order->payment_type) : ?>
              <?= $pagType->name; ?>
              <?php endif; ?>
              <?php endforeach; ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>Cash payment:</strong> <?php if ($order->cash_payment) : ?>
              <?= $order->cash_payment; ?>%<?php endif; ?>
            </td>
            <td>
              <strong>Payment conditions:</strong> <?php foreach ($customerCreditDeadline as $pagTerm) : ?>
              <?php if ($pagTerm->id == $order->id_payment_term) : ?>
              <?= $pagTerm->deadline; ?>
              <?php endif; ?>
              <?php endforeach; ?>
            </td>
          </tr>

          <tr>
            <td>
              <strong>Order Date:</strong> <?= date('d/m/Y', strtotime($order->order_date)); ?>
            </td>
            <td>
            </td>
          </tr>


          <tr>
            <td colspan='2'>
              <hr>
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <h3>ORDER SUMMARY</h3>
              <?php include __DIR__ . "/inc/orderTablePrintPDF.php";
              ?>
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <hr>
            </td>
          </tr>
          <tr>
            <td colspan='2' style="background-color: #f2f7ff; padding:5px 0">
              <strong>Comments:</strong> <?= $order->comments; ?>
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <hr>
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <strong>Delivery type:</strong> <?php foreach ($deliveryType as $dt) : ?>
              <?php if ($dt->id == $order->delivery) : ?>
              <?= $dt->name; ?>
              <?php endif; ?>
              <?php endforeach; ?>
            </td>
          </tr>
          <?php if ($order->delivery == 3) : ?>
          <tr>
            <td colspan='2'>
              <strong>Pickup Address:</strong> Rijk Zwaan Holambra, Centro
            </td>
          </tr>
          <?php else : ?>
          <tr>
            <td>
              <strong>Address:</strong> <?= $address->address_1; ?> <?= $address->address_2; ?>
            </td>
            <td>
              <strong>City:</strong> <?= $address->city; ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>State:</strong> <?php foreach ($states as $state) : ?>
              <?php if ($address->id_state == $state->id) : ?>
              <?= $state->name; ?>
              <?php endif; ?>
              <?php endforeach; ?>
            </td>
            <td>
              <strong>Zip Code:</strong> <?= $address->zipcode; ?>
            </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td colspan='2'>
              <hr>
            </td>
          </tr>
          <tr>
            <td></td>
            <td style="background-color: #f2f7ff; padding:5px 0">
              <strong>Net value with ICMS:</strong> R$ <?= number_format($total->value_icms, 2, ',', '.'); ?>
            </td>
          </tr>
          <?php if ($order->delivery == 3) : ?>
          <?php else : ?>
          <tr>
            <td></td>
            <td style="background-color: #f2f7ff; padding:5px 0">
              <strong>Freight with ICMS:</strong> <span id="freight_value">R$
                <?= number_format($order->freight, 2, ',', '.'); ?>
              </span>
            </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td></td>
            <td style="background-color: #f2f7ff; padding:5px 0">
              <strong>Total:</strong> R$ <?= number_format($total->value_icms + $order->freight, 2, ',', '.'); ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- END INVOICE -->