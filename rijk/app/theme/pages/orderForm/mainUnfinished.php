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
                <div class="container">
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
<div class="modal fade modal-right" id="new" tabindex="-1" role="dialog" aria-labelledby="new" aria-hidden="true"
  role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-right modal-dialog-right modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="order"></div>
      </div>
    </div>
  </div>
</div>

<div id="alertModalDelete" class="modal fade" tabindex="-1" aria-labelledby="alertModalDelete" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="w4rAnimated_checkmark">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" class="errorSup">
                        <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                        <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" />
                        <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" />
                    </svg>
                </div>
                <h3 class="swal2-title text-center mensagemID" id="mensagem">
                    <h3 class="text-3xl text-center text-danger">
                          To delete this order, remove all items within the order!
                        <div class="swal2-actions text-center">
                            <button type="button" class="buttonAlert" data-dismiss="modal" aria-label="Close">OK</button>
                        </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/table-sortable.css"); ?>" rel="stylesheet" media="screen">
<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/table-sortable.js"); ?>" charset="UTF-8"></script>
<script src="<?= url("theme/assets/js/pages/orderUnfinished.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>