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
                                <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
                                    <button class="btn btn-success float-right mb-3 ml-3" data-bs-toggle="modal" data-bs-target="#new"><i class="fa fa-plus"></i> <?= $title; ?></button>
                                    <a class="btn btn-danger float-right mb-3 mr-4" href="<?= url("export/products"); ?>" target="_blank"><i class="fa fa-download"></i> Generate Stock</a>
                                <?php endif; ?>

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
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable full-width" role="document" style="width: 100% !important; max-width: 900px !important;">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" id="form" action="<?= url("product/new"); ?>">
                    <h4 class="card-title text-left">New <?= $title; ?></h4>
                    <hr>
                    <p class="text-left">Registration of new products</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="name">Product</label>
                                <input type="text" class="form-control " required name="input_name" id="input_name" value="">
                            </div>
                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="id_crop">Crop</label>
                                <select class="form-select" id="id_crop" name="id_crop" required>
                                    <option value="0">Select</option>
                                    <?php foreach ($productsCrop as $crop) : ?>
                                        <option value="<?= $crop->id; ?>"><?= $crop->name; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="id_variety">Variety</label>
                                <select class="form-select" id="id_variety" name="id_variety" required>
                                    <option value="0">Select</option>
                                    <?php foreach ($variety as $var) : ?>
                                        <option value="<?= $var->id; ?>"><?= $var->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- <div class="form-group position-relative mb-4 slug-set">
                                <label for="id_variety">Calibre</label>
                                <select class="form-select" id="id_calibre" name="id_calibre">
                                    <option>Select</option>
                                    <?php foreach ($calibre as $cal) : ?>
                                        <option value="<?= $cal->id; ?>"><?= $cal->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> -->
                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="id_sales_unit">Sales unit</label>
                                <select class="form-select" id="id_sales_unit" name="id_sales_unit" required>
                                    <option value="0">Select</option>
                                    <?php foreach ($salesUnit as $su) : ?>
                                        <option value="<?= $su->id; ?>"><?= $su->type; ?> - <?= $su->info; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="id_chemical_treatment">Chemical Treatment</label>
                                <select class="form-select" id="id_chemical_treatment" name="id_chemical_treatment" required>
                                    <option value="0">Select</option>
                                    <?php foreach ($productsChemicalTreatment as $ct) : ?>
                                        <option value="<?= $ct->id; ?>"><?= $ct->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- FIM Tabela Stock -->
                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="batch">Batch</label>
                                <input type="text" class="form-control " required name="batch" id="batch" value="">
                            </div>
                            <div class="form-group position-relative mb-0 slug-set">
                                <label for="ValidityValidity">Validity</label>
                                <input type="text" id="maturity" value="" name="maturity" class="form-control " /><br />
                            </div>

                            <div class="form-group position-relative mb-4 slug-set">
                                <label for="id_chemical_treatment">Status</label>
                                <div>
                                    <label class="switch" for="checkbox">
                                        <input type="checkbox" id="checkbox" name="status" />
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>

                            <input type="hidden" name="id" id="id" value="">
                        </div>

                    </div>
                    <button class="btn btn-success btn-block btn-lg shadow-lg">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet" media="screen">
<link href="<?= url("theme/assets/css/table-sortable.css"); ?>" rel="stylesheet" media="screen">
<link href="<?= url("theme/assets/css/checkbox.css"); ?>" rel="stylesheet" media="screen">

<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/bootstrap-datetimepicker.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/table-sortable.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/products.js"); ?>" charset="UTF-8"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>