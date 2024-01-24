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
        <div class="accordion-container">
            <div class="set">
                <a href="#" class="active">
                    <i class="fa fa-users"></i>
                    Customer
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div class="content" style="display: block;">
                    <?php include __DIR__ . "/../../pages/orderForm/steps/stepOne.php"; ?>
                </div>
            </div>

            <div class="set">
                <a href="#" class="disabled">
                    <i class="fa fa-list-ol"></i>
                    Commercial Order
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div class="content">

                </div>
            </div>

            <div class="set">
                <a href="#" class="disabled">
                    <i class="fa fa-plus"></i>
                    Bonus order
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div class="content">

                </div>
            </div>

            <div class="set">
                <a href="#" class="disabled">
                    <i class="fa fa-clone"></i>
                    Salanova
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div class="content">

                </div>
            </div>
            <div class="set">
                <a href="#" class="disabled">
                    <i class="fa fa-percent"></i>
                    Aditional Discount
                    <div class="float-right"><i class="fa fa-chevron-down"></i></div>
                </a>
                <div class="content">

                </div>
            </div>

        </div>
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
<script type="text/javascript" src="<?= url("theme/assets/js/pages/orderFormStepsInit.js"); ?>"></script>
<?php $v->end(); ?>