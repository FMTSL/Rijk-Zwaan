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
                    <h3 class="text-3xl"><?= $title; ?> #<?= $orderNumber; ?></h3>
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
        <div class="accordion-container rounded">
            <div class="set stepOneUpdate rounded">
                <a href="#" class="active">
                    <i class="fa fa-users"></i>
                    Customer
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div id="stepOneUpdate" class="content" style="display: block;">
                </div>
            </div>

            <div class="set stepTwoUpdate">
                <a href="#" class="active">
                    <i class="fa fa-list-ol"></i>
                    Commercial Order
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div id="stepTwoUpdate" class="content" style="display: block;"></div>
            </div>

            <div class="set stepTreeUpdate">
                <a href="#" class="active">
                    <i class="fa fa-plus"></i>
                    Bonus order
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div id="stepTreeUpdate" class="content" style="display: block;"></div>
            </div>

            <div class="set stepFourUpdate">
                <a href="#" class="active">
                    <i class="fa fa-clone"></i>
                    Salanova
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div id="stepFourUpdate" class="content" style="display: block;"></div>
            </div>

            <div class="set stepSixUpdate">
                <a href="#" class="active">
                    <i class="fa fa-percent"></i>
                    Aditional Discount
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div id="stepSixUpdate" class="content" style="display: block;"></div>
            </div>
        </div>

        <section class="section mt-10" id="valorTotal">
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
<script type="text/javascript" src="<?= url("theme/assets/js/bootstrap-datetimepicker.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/select2.min.js"); ?>"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/orderFormSteps.js"); ?>"></script>
<script>
    $.get(`/order-to-order/<?= $id; ?>/<?= $clientid; ?>/<?= $orderNumber; ?>/step-one`, function(dd) {
        $('#stepOneUpdate').html(dd);
    });
    $.get(`/order-to-order/<?= $id; ?>/<?= $clientid; ?>/<?= $orderNumber; ?>/step-two`, function(dd) {
        $('#stepTwoUpdate').html(dd);
    });

    $.get(`/order-to-order/<?= $id; ?>/<?= $clientid; ?>/<?= $orderNumber; ?>/step-tree`, function(dd) {
        $('#stepTreeUpdate').html(dd);
    });

    $.get(`/order-to-order/<?= $id; ?>/<?= $clientid; ?>/<?= $orderNumber; ?>/step-four`, function(dd) {
        $('#stepFourUpdate').html(dd);
    });

    $.get(`/order-to-order/<?= $id; ?>/<?= $clientid; ?>/<?= $orderNumber; ?>/step-six`, function(dd) {
        $('#stepSixUpdate').html(dd);
    });

    $.get(`/order-to-order-total/<?= $id; ?>/<?= $clientid; ?>/total`, function(dd) {
        $('#valorTotal').html(dd);
    });


    function carregaProdutos() {
        const id = $("#id_order").val();
        const clientid = $("#client_id").val();
        $("#stepTwoUpdate #productList, #stepTreeUpdate #productList_bonus_order, #stepFourUpdate #productList_sala_nova, #stepSixUpdate #productList_aditional_discount, #valorTotal").html("");
        $.get(
            `/order-to-order-list/${id}/${clientid}/step-two-products`,
            function(dd) {
                $("#stepTwoUpdate #productList").html(dd);
            }
        );

        $.get(
            `/order-to-order-list/${id}/${clientid}/step-tree-products`,
            function(dd) {
                $("#stepTreeUpdate #productList_bonus_order").html(dd);
            }
        );

        $.get(
            `/order-to-order-list/${id}/${clientid}/step-four-products`,
            function(dd) {
                $("#stepFourUpdate #productList_sala_nova").html(dd);
            }
        );

        $.get(
            `/order-to-order-list/${id}/${clientid}/step-six-products`,
            function(dd) {
                $("#stepSixUpdate #productList_aditional_discount").html(dd);
            }
        );

        $.get(
            `/order-to-order-total/${id}/${clientid}/total`,
            function(dd) {
                $("#valorTotal").html(dd);
            }
        );

    }
    $("select").select2();
</script>
<?php $v->end(); ?>