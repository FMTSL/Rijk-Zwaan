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
        <section class="section">
            <div class="row" id="table-striped">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <?php if ($userRoles == 0 || $userRoles == 1) : ?>
                                    <a class="btn btn-success float-right mb-3" href="<?= url("order-to-order"); ?>"><i class="fa fa-plus"></i> <?= $title; ?></a>
                                <?php endif; ?>
                                <div class="page-container">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="row mt-5 mb-3 col-md-12 align-items-center g-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" placeholder="Search in table..." id="searchField">
                                        </div>
                                            <div class="col-md-2">
                                                <div class="col-md-12 d-flex">
                                                    <span class="pr-3">Rows Per Page:</span>
                                                    <div class="d-flex justify-content-end">
                                                        <select class="custom-select" name="rowsPerPage" id="changeRows">
                                                            <option value="1">1</option>
                                                            <option value="5" selected>5</option>
                                                            <option value="10">10</option>
                                                            <option value="15">15</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Status -->
                                            <div class="col-md-3">
                                                <div class="col-md-12 d-flex align-items-center">
                                                    <span class="pr-3">Assist Status:</span>
                                                    <div class="d-flex justify-content-end">
                                                        <select class="custom-select" name="assistStatus" id="statusFilter">
                                                            <option value="">All</option>
                                                            <option value="Pending Approval">Pending Approval</option>
                                                            <option value="Order Approved">Order Approved</option>
                                                            <option value="In Process">In Process</option>
                                                            <option value="Sent">Sent</option>
                                                            <option value="Cancelled">Cancelled</option>
                                                            <option value="Quotation">Quotation</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                             <!-- Filtro por data -->
                                            <div class="col-md-3">
                                                <div class="col-md-12 d-flex align-items-center">
                                                    <span class="pr-3">Date:</span>
                                                    <div class="d-flex justify-content-end">
                                                        <input type="date" class="form-control" id="dateFilter">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Sales Representative -->
                                            <!-- <div class="col-md-3">
                                                <div class="col-md-12 d-flex">
                                                    <span class="pr-3 d-none">Sales Representative:</span>
                                                    <div class="d-flex justify-content-end">
                                                        <input type="text" class="form-control" placeholder="Enter Sales Representative" id="salesmanFilter">
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-md-3">
                                                <div class="col-md-12 d-flex align-items-center">
                                                    <span class="pr-3">Sales Representative:</span>
                                                    <div class="d-flex justify-content-end">
                                                        <select class="custom-select" id="salesmanFilter">
                                                            <option value="">All</option>
                                                            <option value="ANDRESSA">Andressa</option>
                                                            <option value="BAHIA">Bahia</option>
                                                            <option value="ELAINE">Elaine</option>
                                                            <option value="ERIC">Eric</option>
                                                            <option value="GABRIEL">Gabriel</option>
                                                            <option value="JOAO VITOR">João Vitor</option>
                                                            <option value="JOSE FRANCISCO">José Francisco</option>
                                                            <option value="LAFAYETE">Lafayete</option>
                                                            <option value="LIONEL">Lionel</option>
                                                            <option value="MARCELO">Marcelo</option>
                                                            <option value="RAFAEL">Rafael</option>
                                                            <option value="RAFAELA">Rafaela</option>
                                                            <option value="RENAN">Renan</option>
                                                            <option value="RENATO">Renato</option>
                                                            <option value="RICARDO">Ricardo</option>
                                                            <option value="RUY">Ruy</option>
                                                            <option value="TIAGO">Tiago</option>
                                                            <option value="VINICIUS T">Vinicius T</option>
                                                            <option value="WEBER">Weber</option>
                                                            <!-- Adicione mais opções de vendedores aqui, se necessário -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="root"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="modal fade modal-right" id="new" tabindex="-1" role="dialog" aria-labelledby="new" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right modal-dialog-right modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="order"></div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/table-sortable.css"); ?>" rel="stylesheet" media="screen">
<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/table-sortable.js"); ?>" charset="UTF-8"></script>
<script src="<?= url("theme/assets/js/pages/order.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>
