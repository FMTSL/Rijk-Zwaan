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
                                <button class="btn btn-success float-right mb-3" data-bs-toggle="modal" data-bs-target="#new"><i class="fa fa-plus"></i> <?= $title; ?></button>

                                <div class="page-container">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="row mt-5 mb-3 col-md-8">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" placeholder="Search in table..." id="searchField">
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <span class="pr-3">Rows Per Page:</span>
                                        </div>
                                        <div class="col-md-2">
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
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" id="form" action="<?= url("customer-category/new"); ?>">
                    <div class="envClass">
                        <h4 class="card-title text-left">New <?= $title; ?></h4>
                        <hr>
                        <p class="text-left">Item registration</p>

                        <div class="row mb-3">
                            <div class="col-md-9">
                                <div class="form-group position-relative slug-set">
                                    <label for="input_name">Name</label>
                                    <input type="text" id="input_name" name="input_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group position-relative">
                                    <label for="id_category">Code</label>
                                    <input type="number" id="code" name="code" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group position-relative has-icon-right">
                                    <label for="id_category">Discount Basic</label>
                                    <input type="number" id="basic_discount" name="basic_discount" class="form-control">
                                    <div class="form-control-icon mt-3">
                                        %
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative has-icon-right">
                                    <label for="id_category">Discount cash payment</label>
                                    <input type="number" id="cash_payment_discount" name="cash_payment_discount" class="form-control">
                                    <div class="form-control-icon mt-3">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group position-relative has-icon-right">
                                    <label for="id_category">Discount sales targets</label>
                                    <input type="number" id="goal_discount" name="goal_discount" class="form-control">
                                    <div class="form-control-icon mt-3">
                                        %
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative has-icon-right">
                                    <label for="id_category">1st year bonus</label>
                                    <input type="number" id="goal_introduction" name="goal_introduction" class="form-control">
                                    <div class="form-control-icon mt-3">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id" value="">
                        <button class="btn btn-success btn-block btn-lg shadow-lg">Register</button>
                    </div>
                    <div class="envClassPit hide">
                        <h4 class="card-title text-left">Prazo de crédito</h4>
                        <hr>
                        <p class="text-left">Lista completa dos prazos de crédito disponíveis, para atrelar a categoria dos clientes</p>
                        <?php if ($deadline) : ?>
                            <ul class="list-unstyled mb-0 row">
                                <?php foreach ($deadline as $dline) : ?>
                                    <li class="d-inline-block me-2 mb-1">
                                        <div class="form-check">
                                            <div class="checkbox" data-id-cat="">
                                                <input type="checkbox" id="deadline_<?= $dline->id; ?>" name="deadline_<?= $dline->id; ?>" class="form-check-input" value="<?= $dline->id; ?>">
                                                <label for="checkbox1" class="row">
                                                    <span class="col-md-6"><?= $dline->type == 0 ? "< " . number_format($dline->valor, 2, ',', '.') : "> " . number_format($dline->valor, 2, ',', '.'); ?></span>
                                                    <span class="col-md-6 align-content-end"><?= $dline->deadline; ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <div class="alert alert-secondary text-center">No items registered!</div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/table-sortable.css"); ?>" rel="stylesheet" media="screen">
<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/table-sortable.js"); ?>" charset="UTF-8"></script>
<script src="<?= url("theme/assets/js/pages/customerCategory.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>