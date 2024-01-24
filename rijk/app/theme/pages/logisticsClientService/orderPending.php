<?php if ($order->status == 1) : ?>
<?php $v->layout("template/themeOff"); ?>
<div id="mainOff" class="col-md-8">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 order-md-1 order-las text-center">
          <h3 class="text-3xl"><?= $title; ?> #<?= $order->order_number; ?></h3>
          <p class="text-subtitle text-muted"><?= $description; ?></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">

        </div>
      </div>
    </div>
  </div>
  <div class="page-content">
    <section class="section mb-4">
      <div class="bg-white">
        <div class="card-body ">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="id_customer">Customer</label>
                <input type="text" id="" name="" class="form-control" value="<?= $client->full_name; ?>" disabled>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="id_category">Category</label>
                <input type="text" id="" name="" class="form-control" value="<?= $customerCategory->name; ?>" disabled>
              </div>
            </div>
            <div class="col-md-5">
              <div class="row">
                <div
                  class=" cash_payment_type <?php if ($order->payment_type == 1) : ?>col-md-3 <?php else : ?>col-md-7 <?php endif; ?>">
                  <div class="form-group">
                    <label for="request_number">Payment Type</label>
                    <?php foreach ($customerPaymentType as $pagTerm) : ?>
                    <?php if ($order->payment_type == $pagTerm->id) : ?>
                    <input type="text" id="" name="" class="form-control" value="<?= $pagTerm->name; ?>" disabled>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="form-group position-relative cash_payment col-md-3"
                  <?php if ($order->payment_type == 1) : ?>style="display: block;" <?php else : ?>style="display: none;"
                  <?php endif; ?>>
                  <label for="helperText">Cash payment</label>
                  <input type="text" id="" name="" class="form-control" value="<?= $order->cash_payment; ?>%" disabled>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label for="id_payment_term">Payment conditions</label>
                    <input type="text" id="" name="" class="form-control" value="<?= $tipoPagamento->deadline; ?>"
                      disabled>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php include __DIR__ . "/inc/orderTable.php"; ?>
    <section class="section price_table">
      <div class="card">
        <div class="card-body">
          <table style="width: 30%; float: right;">
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
                <div id="freight_value">R$ <?= number_format($order->freight - $order->tax_value, 2, ',', '.'); ?></div>
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
                    R$ <?= number_format($totalNotICMS->value + ($order->freight - $order->tax_value), 2, ',', '.'); ?>
                  </div>
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
            <div class="col-md-12">
              <div class="row">

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="delivery">Sales Representatives</label>
                    <p><?= $salesman->name; ?></p>
                  </div>

                </div>

                <div class="col-md-5">
                  <div class="form-group">
                    <label for="delivery_address">E-mail</label>
                    <p><?= $salesmanUser->email; ?></p>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="disabledInput">Phone</label>
                    <p><?= $salesmanUser->phone; ?></p>
                  </div>
                </div>

              </div>


            </div>
          </div>



        </div>
      </div>
    </section>


    <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
    <section class="section">
      <div class="card">
        <div class="card-body">
          <?php if ($order->status == 11) : ?>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="id_customer">Status</label>
                <strong style="color: red; padding-top: 5px;">Order canceled</strong>
              </div>
            </div>
          </div>
          <?php else : ?>
          <form method="post" id="form" action="<?= url("order-to-order/logistic/{$order->id}"); ?>">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label for="id_customer">Change Status</label>
                  <select class="form-select" id="status" name="status">
                    <?php foreach ($status as $stat) : ?>
                    <?php if ($stat->id == 2 || $stat->id == 11) : ?>
                    <option value="<?= $stat->id; ?>" <?php if ($order->status == $stat->id) : ?> selected
                      <?php endif; ?>><?= $stat->name; ?></option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative pt-1">
                      <button class="btn btn-success float-left mt-4"></i> Change status</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <?php endif; ?>
        </div>

      </div>
    </section>
    <?php endif; ?>

  </div>
</div>

<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet" media="screen">
<link href="<?= url("theme/assets/css/select2.min.css"); ?>" rel="stylesheet" media="screen" />
<link href="<?= url("theme/assets/css/steps.css"); ?>" rel="stylesheet" media="screen" />
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
<script type="text/javascript" src="<?= url("theme/assets/js/pages/logistics.js"); ?>"></script>
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

<?php else :
  redirect("/login");
endif; ?>