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
                                <form method="post" id="form" action="<?= url("import/products/stock/new"); ?>" enctype="multipart/form-data">
                                    <h4 class="card-title text-left"><?= $title; ?></h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group position-relative mb-4 slug-set">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="csv" name="csv">
                                                    <label class="custom-file-label" for="csv">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success btn-block btn-lg">Import</button>
                                        </div>
                                        <div class="col-md-4"></div>
                                </form>
                            </div>
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
<?php $v->start("script"); ?>
<link href="<?= url("theme/assets/css/dropzone.css"); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?= url("theme/assets/js/bootstrap-datetimepicker.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/locales/bootstrap-datetimepicker.pt-BR.js"); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= url("theme/assets/js/pages/imports.js"); ?>" charset="UTF-8"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>