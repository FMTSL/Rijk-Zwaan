<form method="post" id="formStepOne" action="<?= url("order-to-order/form/new-step-one"); ?>">
  <div class="card-body ">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="id_customer">Customer <span style="color: red;">*</span></label>
          <select class="form-select selectSearch" id="id_customer" name="id_customer" required>
            <option value="0">Select</option>
            <?php foreach ($client as $cli) : ?>
            <option value="<?= $cli->id; ?>"><?= $cli->full_name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <label for="id_category">Category</label>
          <select class="form-select" id="id_category" name="category" disabled>
            <option value="0">Select</option>
            <?php foreach ($customerCategory as $cat) : ?>
            <option value="<?= $cat->id; ?>"><?= $cat->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col-md-6 cash_payment_type">
        <div class="form-group">
          <label for="request_number">Payment Type <span style="color: red;">*</span></label>
          <select class="form-select" id="payment_type" name="payment_type" required disabled>
            <option value="0">Select</option>
            <?php foreach ($customerPaymentType as $pagTerm) : ?>
            <option value="<?= $pagTerm->id; ?>"><?= $pagTerm->name; ?></option>
            <?php endforeach; ?>
          </select>

        </div>
      </div>

      <div class="position-relative cash_payment col-md-3" style="display: none;">
        <label for="helperText">Cash payment</label>
        <select class="form-select" id="cash_payment" name="cash_payment">
          <option value="0">Select</option>
          <option value="1">1%</option>
          <option value="2">2%</option>
          <option value="3">3%</option>
        </select>

      </div>

    </div>

    <div class="row">
      <div class="col-md-5"></div>
      <div class="col-md-2 mt-3 mb-3"><button type="submit" id="enviaForm"
          class="btn btn-success btn-block btn-lg">Continue</button></div>
      <div class="col-md-5"></div>
    </div>
  </div>
  <input type="hidden" id="orderNumber" name="orderNumber" value="<?= $orderNumber; ?>">
</form>