<form method="post" id="formStepOne" action="<?= url("/order/form/step-one/update"); ?>">
  <div class="card-body ">
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label for="id_customer">Customer</label>
          <select class="form-select selectSearch" id="id_customer" name="id_customer" disabled>
            <option value="<?= $client->id; ?>" <?php if ($client->id == $client->id) : ?>selected<?php endif; ?>>
              <?= $client->full_name; ?></option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="id_category">Category</label>
          <select class="form-select" id="id_category" name="category" disabled>
            <option value="0">Select</option>
            <?php foreach ($customerCategory as $cat) : ?>
            <option value="<?= $cat->id; ?>"
              <?php if ($cat->id == $client->id_category_customer) : ?>selected<?php endif; ?>><?= $cat->name; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="row">
          <div
            class="<?php if ($order->payment_type == 1) : ?> col-md-7 <?php else : ?> col-md-12 <?php endif; ?> cash_payment_type">
            <div class="form-group">
              <label for="request_number">Payment Type</label>
              <select class="form-select" id="payment_type" name="payment_type" disabled>
                <option value="0">Select</option>
                <?php foreach ($customerPaymentType as $pagTerm) : ?>
                <option value="<?= $pagTerm->id; ?>"
                  <?php if ($pagTerm->id == $order->payment_type) : ?>selected<?php endif; ?>><?= $pagTerm->name; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group position-relative cash_payment col-md-5" <?php if ($order->payment_type == 1) : ?>
            style="display: block;" <?php else : ?> style="display: none;" <?php endif; ?>>
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
      <input type="hidden" id="id_payment_term_condition" name="id_payment_term_condition"
        value="<?= $order->id_payment_term; ?>">
      <input type="hidden" id="id_order" name="id_order" value="<?= $order->id; ?>">
      <div class="carrega"></div>
</form>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/steps/stepiOneUpdate.js"); ?>"></script>