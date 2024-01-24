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
                <label for="id_customer">Customer</label>
                <input type="text" id="" name="" class="form-control" value="<?= $client->full_name; ?>" disabled>
              </div>

              <?php if ($order->salanova == 0) : ?>

                <div class="form-group">
                  <label for="id_category">Category</label>
                  <input type="text" id="" name="" class="form-control" value="<?= $customerCategory->name; ?>" disabled>
                </div>

              <?php else : ?>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="helperText">Salanova</label>
                      <input type="text" id="additional_discount" name="" class="form-control" value="Yes" disabled>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="request_number">Salanova Discount</label>
                      <?php foreach ($salanova as $sal) : ?>
                        <?php if ($sal->id == $order->id_salanova) : ?>
                          <input type="text" id="" name="" class="form-control" value="<?= $sal->discount; ?>%" disabled>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>


            </div>
            <div class="col-md-6">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="helperText">Additional discount</label>
                    <input type="text" id="additional_discount" name="additional_discount" class="form-control" value="<?= $order->additional_discount; ?>%" disabled>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="request_number">Payment Type</label>
                    <?php foreach ($customerPaymentType as $pagType) : ?>
                      <?php if ($pagType->id == $order->payment_type) : ?>
                        <input type="text" id="payment_type" name="payment_type" class="form-control" value="<?= $pagType->name; ?>" disabled>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>



              <div class="row">
                <div class="col-md-4" <?php if ($order->cash_payment == 0) : ?>style="display: none;" <?php endif; ?>>
                  <div class="form-group position-relative cash_payment">
                    <label for="helperText">Cash payment</label>
                    <input type="text" id="cash_payment" name="cash_payment" class="form-control" value="<?= $order->cash_payment; ?>%" disabled>
                  </div>
                </div>

                <div <?php if ($order->cash_payment == 0) : ?>class="col-md-8" <?php else : ?> class="col-md-4" <?php endif; ?>>
                  <div class="form-group">
                    <label for="id_payment_term">Payment conditions</label>
                    <?php foreach ($customerCreditDeadline as $pagTerm) : ?>
                      <?php if ($pagTerm->id == $order->id_payment_term) : ?>
                        <input type="text" id="id_payment_term" name="id_payment_term" class="form-control" value="<?= $pagTerm->deadline; ?>" disabled>
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

              <div class="form-group bonus" <?php if ($order->bonus_order == 0) : ?> style="display: none;" <?php endif; ?>>
                <label for="bonus">Bonus</label>
                <?php foreach ($bonusOrder as $bonus) : ?>
                  <?php if ($bonus->id == $order->id_bonus_order) : ?>
                    <input type="text" id="bonus" name="bonus" class="form-control" value="<?= $bonus->name; ?>" disabled>
                    <input type="hidden" id="bonus_discount" name="bonus_discount" class="form-control" value="<?= $bonus->discount; ?>">
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="" id="table-striped">
        <div class="col-12"></div>
        <div class="card">
          <div class="card-content">

            <div class="card-body">

              <div class="col-md-12 bg-light-secondary rounded">
                <form method="post" id="cart" action="<?= url("order-to-order/carrinho/new"); ?>">
                  <input type="hidden" id="cliente_id" name="cliente_id" value="<?= $client->id; ?>">
                  <div class="row pt-2 pb-2 p-3">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="variety">Variety</label>
                        <select class="form-select selectSearch" id="variety" name="variety">
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
                        <label for="id_stock">Packaging</label>
                        <select class="form-select" id="id_stock" name="id_stock" disabled>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="quantity">Quantity in MX</label>
                        <input type="text" id="quantity" name="quantity" class="form-control" disabled>
                      </div>
                    </div>

                    <div class="col-md-2 cash_payment">
                      <div class="form-group position-relative">
                        <label for="id_category">Volume condition</label>
                        <select class="form-select" id="discount" name="discount" disabled>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-1">
                      <button class="btn btn-success float-left mt-4" id="btn_prod"><i class="fa fa-plus"></i></button>
                    </div>
                  </div>
                </form>
              </div>
              <hr>
              <div id="productList">
                <?php if ($itens) : ?>
                  <div class="table-responsive-w-100">
                    <table id="tabela" class="table table-striped mb-0">
                      <thead>
                        <tr>
                          <th class="">Variety</th>

                          <th class="">Packaging</th>
                          <th class="">Quantity in MX</th>
                          <th class="">Price per MX</th>
                          <th class="">Net value</th>
                          <th class="">Total Discount</th>
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
                                <td class="text-bold-500"><?= $item->quantity; ?></td>
                                <td class="text-bold-500">
                                  <?php if ($order->bonus_order == 1) : ?>
                                    --
                                  <?php else : ?>
                                    R$ <?= $item->value; ?>
                                  <?php endif; ?></td>
                                <td class="text-bold-500">
                                  <?php if ($order->bonus_order == 1) : ?>
                                    --
                                  <?php else : ?>
                                    R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
                                  <?php endif; ?>
                                </td>
                                <td class="text-bold-500">
                                  <?php if ($order->bonus_order == 1) : ?>
                                    --
                                  <?php else : ?>
                                    R$
                                    <?php
                                    $valor = ($item->quantity * $item->value_icms);
                                    $porcentagem = $order->discount + $order->additional_discount + $order->cash_payment;
                                    $resultado = $valor * ($porcentagem / 100);
                                    ?><?= number_format($valor - $resultado, 2, ',', '.'); ?>

                                  <?php endif; ?>
                                </td>
                                <td class="text-bold-500"><button onclick="deletar(<?= $item->id; ?>)" class="btn btn-danger"><i class="fa fa-times"></i></button></td>
                              </tr>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else : ?>
                  <div class="alert alert-secondary text-center">No products for this order!</div>
                <?php endif; ?>
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
          <div class="col-md-4">Gross value:
            <?php if ($order->bonus_order == 1) : ?>
              <?php
              $valor = $total->value;
              foreach ($bonusOrder as $bonus) :
                if ($bonus->id == $order->id_bonus_order) :
                  $porcentagem = $bonus->discount;
                  $resultado = $valor * ($porcentagem / 100);
                endif;
              endforeach;
              ?>
              R$ <?= number_format($total->value - $resultado, 2, ',', '.'); ?>
            <?php else : ?>
              R$ <?= number_format($total->value, 2, ',', '.'); ?>
            <?php endif; ?>

          </div>
          <div class="col-md-4 ">Total:
            <?php if ($order->bonus_order == 1) : ?>
              <?php
              $valor = $total->value;
              foreach ($bonusOrder as $bonus) :
                if ($bonus->id == $order->id_bonus_order) :
                  $porcentagem = $bonus->discount;
                  $resultado = $valor * ($porcentagem / 100);
                endif;
              endforeach;
              ?>
              R$ <?= number_format($total->value - $resultado, 2, ',', '.'); ?>//
            <?php else : ?>

              R$ <?php
                  $valor = $total->value;
                  $porcentagem = $order->discount + $order->additional_discount + $order->cash_payment;
                  $resultado = $valor * ($porcentagem / 100);
                  ?><?= number_format($total->value - $resultado, 2, ',', '.'); ?>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <?php if ($total->value > 2500) : ?>
              <a class="btn btn-success btn-block btn-lg" href="<?= url("order-to-order/step-tree"); ?>/<?= $client->id; ?>/<?= $order->order_number; ?>">Finish</a>
            <?php else : ?>
              <cite> Minimum order over R$ 2500.00</cite>
            <?php endif; ?>
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
<script type="text/javascript" src="<?= url("theme/assets/js/jquery.mask.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/select2.min.js"); ?>"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/orderForm02.js"); ?>"></script>
<?php $v->end(); ?>