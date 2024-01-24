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
                    <h3 class="text-3xl"><?= $title; ?></h3>
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
        <form method="post" id="form" action="<?= url("order-to-order/form/new"); ?>">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_customer">Cliente</label>
                                    <select class="form-select" id="id_customer" name="id_customer" required>
                                        <option>Select</option>
                                        <?php foreach ($client as $cli) : ?>
                                            <option value="<?= $cli->id; ?>"><?= $cli->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="id_category">Categoria</label>
                                    <select class="form-select" id="id_category" name="category" disabled>
                                        <option>Select</option>
                                        <?php foreach ($customerCategory as $cat) : ?>
                                            <option value="<?= $cat->id; ?>"><?= $cat->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="helperText">Desconto adicional</label>
                                            <select class="form-select" id="additional_discount" name="additional_discount" disabled>
                                                <option>Select</option>
                                                <option value="1">1%</option>
                                                <option value="2">2%</option>
                                                <option value="3">3%</option>
                                                <option value="4">4%</option>
                                                <option value="5">5%</option>
                                            </select>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="request_number">Tipo Pagamento</label>
                                            <select class="form-select" id="payment_type" name="payment_type" required disabled>
                                                <option>Select</option>
                                                <?php foreach ($customerPaymentType as $pagTerm) : ?>
                                                    <option value="<?= $pagTerm->id; ?>"><?= $pagTerm->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative cash_payment" style="display: none;">
                                    <label for="helperText">Pagamento a Vista</label>
                                    <select class="form-select" id="cash_payment" name="cash_payment">
                                        <option>Select</option>
                                        <option value="1">1%</option>
                                        <option value="2">2%</option>
                                        <option value="3">3%</option>
                                        <option value="4">4%</option>
                                        <option value="5">5%</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="id_category">Prazo de Pagamento</label>
                                    <select class="form-select" id="id_payment_term" name="id_payment_term" required disabled>
                                        <option>Select</option>
                                        <?php foreach ($customerCreditDeadline as $pagDeadline) : ?>
                                            <option value="<?= $pagDeadline->id; ?>"><?= $pagDeadline->deadline; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="disabledInput">Data de Entrega</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" value="" id="order_date" name="order_date" readonly>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <input type="hidden" id="dtp_input2" value="" name="delivery-date" /><br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <input type="hidden" id="orderNumber" name="orderNumber" value="<?= $orderNumber; ?>">
            <input type="hidden" id="totalParc" name="totalParc" value="">
            <input type="hidden" id="total_form" name="total_form" value="">
            <input type="hidden" id="discount_form" name="discount_form" value="">
            <button type="submit" id="enviaForm" class="hide"></button>
        </form>
        <section class="section">
            <div class="row" id="table-striped">
                <div class="col-12"></div>
                <div class="card">
                    <div class="card-content">

                        <div class="card-body">

                            <div class="col-md-12 bg-light-secondary rounded">
                                <form method="post" id="cart" action="<?= url("order-to-order/carrinho/new"); ?>">
                                    <div class="row pt-2 pb-2 p-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product">Produto</label>
                                                <select class="form-select" id="product" name="product" disabled>
                                                    <option>Select</option>
                                                    <?php foreach ($products as $prod) : ?>
                                                        <option value="<?= $prod->id; ?>">
                                                            <?php foreach ($variety as $var) :
                                                                if ($var->id == $prod->id_variety) :
                                                                    echo "{$var->name} - {$prod->name}";
                                                                endif;
                                                            endforeach; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_stock">Packaging</label>
                                                <select class="form-select" id="id_stock" name="id_stock" disabled>
                                                    <option>Select</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="quantity">Quantidade em MX</label>
                                                <input type="text" id="quantity" name="quantity" class="form-control" disabled>
                                            </div>
                                        </div>

                                        <!-- div class="col-md-2 cash_payment">
                                                    <div class="form-group position-relative has-icon-right">
                                                    <label for="id_category">Pag. antecipado</label>
                                                    <input type="text" id="discount_product" name="discount_product" class="form-control">
                                                    <div class="form-control-icon mt-3">
                                                        %
                                                    </div>
                                                    </div>
                                                </div -->

                                        <div class="col-md-1">
                                            <input type="hidden" id="cliente_id" name="cliente_id">
                                            <button class="btn btn-success float-left mt-4"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr>
                            <div id="productList"></div>
                            <div class="carrega"></div>
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
                    <div class="col-md-3">

                        <div class="form-group  position-relative has-icon-left">
                            <label for="request_number">Número do Pedido</label>
                            <input type="text" class="form-control" id="request_number" name="request_number" readonly="readonly" value="<?= $orderNumber; ?>">
                            <div class="form-control-icon mt-3">#</div>
                        </div>

                    </div>
                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="total">Valor Parcial</label>
                            <input type="text" class="form-control" id="total_parc" name="total_parc" readonly="readonly" value="">
                        </div>

                    </div>
                    <div class="col-md-3">

                        <div class="form-group position-relative has-icon-right">
                            <label for="total">Desconto</label>
                            <input type="text" class="form-control" id="desconto" name="desconto" readonly="readonly" value="">
                            <div class="form-control-icon mt-3">%</div>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="total">Valor Total</label>
                            <input type="text" class="form-control" id="total" name="total" readonly="readonly" value="">
                        </div>

                    </div>


                </div>

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>

                    <div class="col-md-4 mt-5 mb-3">
                        <a class="btn btn-success btn-block btn-lg" id="getOrder">Gerar Pedido</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>
</div>
<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet" media="screen">
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
<script type="text/javascript" src="<?= url("theme/assets/js/bootstrap-datetimepicker.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>" charset="UTF-8"></script>
<script src="<?= url("theme/assets/js/pages/orderForm.js"); ?>"></script>
<?php $v->end(); ?>