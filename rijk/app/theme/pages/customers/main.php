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
                <button class="btn btn-success float-right mb-3" data-bs-toggle="modal" data-bs-target="#new"><i
                    class="fa fa-plus"></i> <?= $title; ?></button>
                <?php endif; ?>

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
                    <div class="col-md-3">
                      <div class="col-md-12 d-flex align-items-center">
                          <span class="pr-3">Euro Status:</span>
                          <div class="d-flex justify-content-end">
                              <select class="custom-select" name="euroStatus" id="euroFilter">
                                  <option value="">All</option>
                                  <option value="true">Yes</option>
                                  <option value="false">No</option>
                              </select>
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
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable full-width"
    role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post" id="form" action="<?= url("customer/new"); ?>">
          <h4 class="card-title text-left">New <?= $title; ?></h4>
          <hr>
          <p class="text-left">Cadastro de novo cliente</p>

          <div class="row mb-4">
            <div class="col-md-4">
              <div class="form-group position-relative mb-4">
                <label for="name">Número de Relação<span style="color: red;">*</span< /label>
                    <input type="text" class="form-control " placeholder="" name="relation_number" id="relation_number"
                      maxlength="9">
              </div>
            </div>

            <div class="col-md-8">
              <div class="form-group position-relative mb-4">
                <label for="name">Name</label>
                <input type="text" class="form-control " placeholder="" name="input_name" id="input_name">
              </div>

            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group position-relative mb-4">
                        <label for="euro">Euro</label>
                        <select class="form-select form-control" id="euro" name="euro">
                            <option value="false" <?= $client->euro == 'false' ? 'selected' : ''; ?>>No</option>
                            <option value="true" <?= $client->euro == 'true' ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                </div>
            </div>

          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <div class="form-group position-relative mb-4">
                <label for="name">Nome Completo</label>
                <input type="text" class="form-control " placeholder="" required name="full_name" id="full_name">

              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group position-relative mb-4">
                <label for="email">Email</label>
                <input type="text" class="form-control " placeholder="" required name="email" id="email">

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group position-relative mb-4">
                <label for="phone">Telefone</label>
                <input type="phone" class="form-control " placeholder="" required name="telephone" id="telephone">

              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group position-relative mb-4">
                <label for="mobile">Celular</label>
                <input type="phone" class="form-control " placeholder="" name="mobile" id="mobile">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group position-relative mb-4">
                <label for="phone">Fax</label>
                <input type="phone" class="form-control " placeholder="" name="fax" id="fax">
              </div>

            </div>


            <div class="col-md-6">
              <div class="form-group position-relative mb-4 slug-set">
                <label for="id_category">Customer type</label>
                <select class="form-select form-control " id="id_category" name="id_category">
                  <option>Select</option>
                  <?php foreach ($categoryCustomerType as $cat) : ?>
                  <option value="<?= $cat->id; ?>" <?php if ($client->id_category == $cat->id) : ?> selected
                    <?php endif; ?>><?= $cat->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group position-relative mb-4 slug-set">
                <label for="id_category_customer">Category</label>
                <select class="form-select form-control " id="id_category_customer" name="id_category_customer">
                  <option>Select</option>
                  <?php foreach ($category as $cat) : ?>
                  <option value="<?= $cat->id; ?>"><?= $cat->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>

          </div>

          <div class="row mb-4">
            <div class="col-md-12">
              <h4>Dados do Vendedor</h4>
              <div class="form-group position-relative mb-4 slug-set">
                <select class="form-select form-control " id="id_salesman" name="id_salesman">
                  <option>Select</option>
                  <?php foreach ($salesman as $sal) : ?>
                  <option value="<?= $sal->id; ?>"><?= $sal->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>
          </div>

          <div class="row mb-4">
            <h4>Dados Adicionais</h4>
            <div class="col-md-6">
              <div class="form-group position-relative mb-4">
                <label for="name">Website</label>
                <input type="text" class="form-control " placeholder="" name="website" id="website">
              </div>

            </div>

            <div class="col-md-12">
              <div class="form-group position-relative mb-4">
                <label for="name">Bio</label>
                <textarea class="form-control " name="bio" id="bio"></textarea>
              </div>

            </div>
          </div>



          <div class="row mb-4">
            <h4>Endereço</h4>
            <div class="col-md-7">
              <div class="form-group position-relative mb-4">
                <label for="name">Endereço</label>
                <input type="text" class="form-control " placeholder="" required name="address_1" id="address_1">
              </div>
            </div>

            <div class="col-md-5">
              <div class="form-group position-relative mb-4">
                <label for="name">Endereço 2</label>
                <input type="text" class="form-control " placeholder="" name="address_2" id="address_2">
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4">
              <div class="form-group position-relative mb-4">
                <label for="name">Cidade</label>
                <input type="text" class="form-control " placeholder="" required name="city" id="city">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group position-relative mb-4">
                <label for="name">CEP</label>
                <input type="text" class="form-control " placeholder="" required name="zipcode" id="zipcode">
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4">
              <div class="form-group position-relative mb-4 slug-set">
                <label for="id_state">Estado</label>
                <select class="form-select form-control " id="id_state" name="id_state">
                  <option>Select</option>
                  <?php foreach ($states as $state) : ?>
                  <option value="<?= $state->id; ?>"><?= $state->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group position-relative mb-4 slug-set">
                <label for="address_country">País</label>
                <select class="form-select form-control " id="id_country" name="id_country">
                  <option value="1">Brazil</option>
                  <?php
                  // foreach ($countrys as $country): 
                  ?>
                  <!--option value="<?= $country->id; ?>"><?= $country->name; ?></option-->
                  <?php
                  // endforeach; 
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type="hidden" name="id" id="id" value="">
          <input type="hidden" name="id_address_id" id="id_address_id" value="">
          <input type="hidden" name="id_customer_id" id="id_customer_id" value="">
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
<script src="<?= url("theme/assets/js/pages/customers.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>
