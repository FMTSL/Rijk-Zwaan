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
                <form method="post" id="form" action="<?= url("packing/new"); ?>">
                    <h4 class="card-title text-left">New <?= $title; ?></h4>
                    <hr>
                    <p class="text-left">Cadastro de novas embalagens</p>
                    <div class="form-group position-relative mb-4 slug-set">
                        <label for="name">Name</label>
                        <input type="text" class="form-control " required name="input_name" id="input_name" value="">
                    </div>
                    <input type="hidden" name="id" id="id" value="">
                    <button class="btn btn-success btn-block btn-lg shadow-lg">Register</button>
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
<script src="<?= url("theme/assets/js/pages/productsPackaging.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>