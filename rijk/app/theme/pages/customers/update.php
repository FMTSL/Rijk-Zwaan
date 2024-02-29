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
                <div class="table-responsive-w-100">
                  <form method="post" id="form" action="<?= url("customer/update"); ?>">
                    <div class="row mb-4">
                      <div class="col-md-3">
                        <div class="form-group position-relative mb-4">
                          <label for="name">Relationship Number</label>
                          <input type="text" class="form-control " value="<?= $client->relation_number; ?>"
                            name="relation_number" id="relation_number">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group position-relative mb-4">
                          <label for="name">Name</label>
                          <input type="text" class="form-control " value="<?= $users->name; ?>" name="input_name"
                            id="input_name">
                        </div>

                      </div>

                      <div class="col-md-3">
                        <div class="form-group position-relative mb-4">
                          <label for="name">CNPJ</label>
                          <input type="text" class="form-control " value="<?= $client->cnpj; ?>" name="cnpj" id="cnpj">
                        </div>
                      </div>


                    </div>

                    <div class="row mb-4">
                      <div class="col-md-6">
                        <div class="form-group position-relative mb-4">
                          <label for="name">Full Name</label>
                          <input type="text" class="form-control " value="<?= $client->full_name; ?>" required
                            name="full_name" id="full_name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group position-relative mb-4">
                          <label for="email">Email</label>
                          <input type="text" class="form-control " value="<?= $client->email; ?>" required name="email"
                            id="email">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group position-relative mb-4">
                          <label for="phone">Telephone</label>
                          <input type="phone" class="form-control " value="<?= $client->telephone; ?>" required
                            name="telephone" id="telephone">

                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group position-relative mb-4">
                          <label for="mobile">Mobile</label>
                          <input type="phone" class="form-control " value="<?= $client->mobile; ?>" name="mobile"
                            id="mobile">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group position-relative mb-4">
                          <label for="phone">Fax</label>
                          <input type="phone" class="form-control " value="<?= $client->fax; ?>" name="fax" id="fax">
                        </div>
                      </div>


                      <div class="col-md-4">
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

                      <div class="col-md-5">
                        <div class="form-group position-relative mb-4 slug-set">
                          <label for="id_category_customer">Category</label>
                          <select class="form-select form-control " id="id_category_customer"
                            name="id_category_customer">
                            <option>Select</option>
                            <?php foreach ($categoryCustomer as $catCustomer) : ?>
                            <option value="<?= $catCustomer->id; ?>"
                              <?php if ($client->id_category_customer == $catCustomer->id) : ?> selected
                              <?php endif; ?>><?= $catCustomer->name; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group position-relative mb-4 slug-set">
                          <label for="special_client">Special Client</label>
                          <select class="form-select form-control " id="special_client" name="special_client">
                            <option>Select</option>
                            <option value="0" <?php if ($client->special_client == 0) : ?> selected <?php endif; ?>>No
                            </option>
                            <option value="1" <?php if ($client->special_client == 1) : ?> selected <?php endif; ?>>Yes
                            </option>

                          </select>
                        </div>
                      </div>

                    </div>

                    <div class="row mb-4">
                      <div class="col-md-12">
                        <h4>Sales Representatives</h4>
                        <div class="form-group position-relative mb-4 slug-set">
                          <select class="form-select form-control " id="id_salesman" name="id_salesman">
                            <?php foreach ($salesman as $sal) : ?>
                            <option value="<?= $sal->id; ?>" <?php if ($client->id_salesman == $sal->id) : ?> selected
                              <?php endif; ?>><?= $sal->name; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                      </div>
                    </div>

                    <div class="row mb-4">
                      <h4>Additional data</h4>
                      <div class="col-md-6">
                        <div class="form-group position-relative mb-4">
                          <label for="name">Website</label>
                          <input type="text" class="form-control " value="<?= $client->website; ?>" name="website"
                            id="website">
                        </div>

                      </div>

                      <div class="col-md-12">
                        <div class="form-group position-relative mb-4">
                          <label for="name">Bio</label>
                          <textarea class="form-control " name="bio" id="bio"><?= $client->bio; ?></textarea>
                        </div>

                      </div>

                      <div class="col-md-3">
                          <div class="form-group position-relative mb-4">
                              <label for="value">Value</label>
                              <input type="checkbox" class="form-check-input" id="value" name="value" value="1" <?php echo $client->value == 1 ? 'checked' : ''; ?>>
                          </div>
                      </div>

                    </div>
                    <div class="row mb-4">
                      <h4 class="col-md-9">Adresses</h4>
                      <div class="col-md-3">
                        <a class="btn btn-success float-right mb-3" data-bs-toggle="modal" data-bs-target="#new"><i
                            class="fa fa-plus"></i> Add Adresses</a>
                      </div>
                      <hr>
                    </div>

                    <div class="table-responsive-w-100">
                      <?php if ($relationshipClientAddress) : ?>
                      <table class="table table-striped mb-0">
                        <thead>
                          <tr>
                            <th class="">Delivery</th>
                            <th class="">Address 1</th>
                            <th class="">Address 2</th>
                            <th class="">Zip Code</th>
                            <th class="">City</th>
                            <th class="">State</th>
                            <th class="">Country</th>
                            <th class="w-125"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($relationshipClientAddress as $rel) : ?>
                          <?php foreach ($address as $item) : ?>
                          <?php if ($item->id == $rel->id_address) : ?>
                          <tr>
                            <td class="text-bold-500">
                              <?php if ($rel->delivery_type == 1) : ?>Yes<?php else : ?> No<?php endif; ?>
                            </td>
                            <td class="text-bold-500"><?= $item->address_1; ?></td>
                            <td class="text-bold-500"><?= $item->address_2; ?></td>
                            <td class="text-bold-500"><?= $item->zipcode; ?></td>
                            <td class="text-bold-500"><?= $item->city; ?></td>
                            <td class="text-bold-500">
                              <?php foreach ($state as $st) :
                                        if ($st->id == $item->id_state) :
                                          echo $st->name;
                                        endif;
                                      endforeach; ?>
                            </td>
                            <td class="text-bold-500">
                              <?php foreach ($country as $coun) :
                                        if ($coun->id == $item->id_country) :
                                          echo $coun->initials;
                                        endif;
                                      endforeach; ?>
                            </td>
                            <td>
                              <a class="btn btn-success" onclick="update(<?= $item->id; ?>)"><i
                                  class="fa fa-edit"></i></a>
                              <a onclick="deletarEnd(<?= $item->id; ?>)" class="btn btn-danger"><i
                                  class="fa fa-times"></i></a>
                            </td>
                          </tr>
                          <?php endif; ?>
                          <?php endforeach; ?>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      <div class="pagination pagination-primary pagination-lg mt-3">
                        <?= $pager; ?>
                      </div>
                      <?php else : ?>
                      <div class="alert alert-secondary text-center">No items registered!</div>
                      <?php endif; ?>
                    </div>


                    <input type="hidden" name="id" id="id" value="<?= $client->id; ?>">
                    <div class="col-md-3 pt-4 float-right pb-4">
                      <button class="btn btn-success btn-block btn-lg shadow-lg">Update</button>
                    </div>
                  </form>
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
        <form method="post" id="formAddress" action="<?= url("customer-address/new"); ?>">
          <h4 class="card-title text-left">New Address</h4>
          <hr>
          <div class="row mb-4">
            <div class="col-md-7">
              <div class="form-group position-relative slug-set">
                <label for="name">Description</label>
                <input type="text" class="form-control " required name="type" id="type" value="">
              </div>
            </div>
            <div class="col-md-5">
              <label for="name">Delivery</label>
              <select class="form-select form-control" id="delivery_type" name="delivery_type">
                <option>Select</option>
                <option value="1">Delivery</option>
                <option value="2">Billing</option>
              </select>
            </div>
          </div>
          <div class="form-group position-relative mb-4">
            <label for="slug">Address 1</label>
            <input type="text" class="form-control  disabled" name="address_1" id="address_1" value="">
          </div>

          <div class="form-group position-relative mb-4">
            <label for="slug">Address 2</label>
            <input type="text" class="form-control  disabled" name="address_2" id="address_2" value="">
          </div>
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="form-group position-relative mb-4">
                <label for="slug">Zip Code</label>
                <input type="text" class="form-control  disabled" name="zipcode" id="zipcode" value="">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group position-relative mb-4">
                <label for="slug">City</label>
                <input type="text" class="form-control disabled" name="city" id="city" value="">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group position-relative mb-4 slug-set">
                <label for="id_state">State</label>
                <select class="form-select form-control " id="id_state" name="id_state">
                  <option>Select</option>
                  <?php foreach ($state as $st) : ?>
                  <option value="<?= $st->id; ?>"><?= $st->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group position-relative mb-4 slug-set">
                <label for="id_state">Country</label>
                <select class="form-select form-control " id="id_country" name="id_country">
                  <option>Select</option>
                  <?php foreach ($country as $ct) : ?>
                  <option value="<?= $ct->id; ?>"><?= $ct->initials; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <input type="hidden" name="id_address" id="id_address" value="">
          <input type="hidden" name="id_customer" id="id_customer" value="<?= $client->id; ?>">
          <button class="btn btn-success btn-block btn-lg shadow-lg" id="actionBtn">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $v->start("script"); ?>
<script src="<?= url("theme/assets/js/pages/customers.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>