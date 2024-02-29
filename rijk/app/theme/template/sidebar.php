<?php $v->start("sidebar"); ?>
<nav class="flex items-center justify-between flex-wrap p-0 bg-white">
  <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
    <div class="text-sm lg:flex-grow">
    </div>
    <div class="text-sm p-2 px-4 text-left bg-[#96bf0d] text-white rounded-tl-lg ounded-bl-lg">
    <strong><?= $userName; ?></strong><br/>
    <?= $userEmail; ?>
  </div>
  </div>
</nav>
<div id="sidebar" class="active">
  <div class="sidebar-wrapper drop-shadow-2xl active bg-green">
    <div class="sidebar-header bg-white">
      <div class="d-flex justify-content-center">
        <div class="logo rounded overflow-hidden">
          <a href="<?= url(); ?>"><img src="<?= url("/theme/assets/images/logo/logo.png"); ?>" alt="Logo" srcset=""></a>
        </div>
        <div class="toggler">
          <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
        </div>
      </div>
    </div>
    <div class="sidebar-menu">
      <ul class="menu">
        <li class="sidebar-title">Menu</li>
        <!--                active-->
        <!--li class="sidebar-item ">
          <a href="<?= url(); ?>" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
          </a>
        </li-->

        <li class="sidebar-item  has-sub">
          <a href="#" class='sidebar-link'>
            <i class="bi bi-collection-fill"></i>
            <span>SALES ORDERS</span>
          </a>
          <ul class="submenu ">
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 2) : ?>
            <li class="submenu-item ">
              <a href="<?= url("order-to-order"); ?>">New Order</a>
            </li>
            <li class="submenu-item ">
              <a href="<?= url("orders"); ?>">Sales orders</a>
            </li>
            <?php endif; ?>
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
            <li class="submenu-item ">
              <a href="<?= url("client-service"); ?>">Client Service</a>
            </li>
            <?php endif; ?>
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 2) : ?>
            <li class="submenu-item ">
              <a href="<?= url("unfinished-orders"); ?>">Unfinished Order</a>
            </li>
            <?php endif; ?>
          </ul>
        </li>

        <li class="sidebar-item  has-sub">
          <a href="#" class='sidebar-link'>
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Products</span>
          </a>
          <ul class="submenu ">
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
            <li class="submenu-item">
              <a href="<?= url("product/stock"); ?>">Products</a>
            </li>
            <?php endif; ?>
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
            <li class="submenu-item">
              <a href="<?= url("product/stock/euro"); ?>">Products Euro</a>
            </li>
            <?php endif; ?>
            <li class="submenu-item">
              <a href="<?= url("product/stock/clone"); ?>">Daily Stock</a>
            </li>
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
            <li class="submenu-item">
              <a href="<?= url("products-crop"); ?>">Crop</a>
            </li>
            <li class="submenu-item">
              <a href="<?= url("varieties"); ?>">Varieties</a>
            </li>

            <li class="submenu-item">
              <a href="<?= url("discounts"); ?>">Discount</a>
            </li>

            <li class="submenu-item">
              <a href="<?= url("sales-unit"); ?>">Sales Unit</a>
            </li>

            <li class="submenu-item">
              <a href="<?= url("packaging"); ?>">Packaging</a>
            </li>

            <li class="submenu-item">
              <a href="<?= url("chemical-treatment"); ?>"> Chemical Treatment</a>
            </li>
            <?php endif; ?>
          </ul>
        </li>

        <li class="sidebar-item  has-sub">
          <a href="#" class='sidebar-link'>
            <i class="bi bi-hexagon-fill"></i>
            <span>Customers</span>
          </a>
          <ul class="submenu ">
            <li class="submenu-item">
              <a href="<?= url("customers"); ?>">Customers</a>
            </li>
            <?php if ($userRoles == 0 || $userRoles == 1 || $userRoles == 4) : ?>
            <li class="submenu-item">
              <a href="<?= url("customer-type"); ?>">Customer type</a>
            </li>
            <!-- <li class="submenu-item">
                            <a href="<?= url("customers-address"); ?>">Address</a>
                        </li> -->
            <li class="submenu-item">
              <a href="<?= url("customer-category"); ?>">Customer category</a>
            </li>
            <li class="submenu-item">
              <a href="<?= url("credit-term"); ?>">Payment conditions</a>
            </li>
            <li class="submenu-item">
              <a href="<?= url("bonus-order"); ?>">Bonus Orders</a>
            </li>
            <li class="submenu-item">
              <a href="<?= url("salanovas"); ?>">Salanova</a>
            </li>
            <?php endif; ?>
          </ul>
        </li>


        <?php if ($userRoles == 0) : ?>
        <li class="sidebar-item  has-sub">
          <a href="#" class='sidebar-link'>
            <i class="bi bi-cloud-arrow-up"></i>
            <span>Data Import</span>
          </a>
          <ul class="submenu ">
            <li class="submenu-item ">
              <a href="<?= url("import/customers"); ?>">Customers</a>
            </li>
            <li class="submenu-item ">
              <a href="<?= url("import/products"); ?>">Products</a>
            </li>
            <li class="submenu-item ">
              <a href="<?= url("import/products/euro"); ?>">Products Euro</a>
            </li>
            <li class="submenu-item ">
              <a href="<?= url("import/products/clone"); ?>">Daily Stock</a>
            </li>
          </ul>
        </li>
        <?php endif; ?>
        <li class="sidebar-item">
          <a href="<?= url("files"); ?>" class='sidebar-link'>
            <i class="bi bi-archive"></i>
            <span>Files</span>
          </a>
        </li>




        <li class="sidebar-title">Account</li>

        <!--li class="sidebar-item  ">
          <a href="#" class='sidebar-link'>
            <i class="bi bi-person-bounding-box"></i>
            <span>My profile</span>
          </a>
        </li -->
        <?php if ($userRoles == 0) : ?>
        <li class="sidebar-item  ">
          <a href="<?= url("users"); ?>" class='sidebar-link'>
            <i class="bi bi-people-fill"></i>
            <span>Users</span>
          </a>
        </li>
        <?php if ($userRoles == 0 || $userRoles == 1) : ?>
        <li class="sidebar-item  ">
          <a href="<?= url("salesman"); ?>" class='sidebar-link'>
            <i class="bi bi-people-fill"></i>
            <span>Sales Representatives</span>
          </a>
        </li>
        <?php endif; ?>

        <li class="sidebar-title">Helpers</li>
        <li class="sidebar-item  ">
          <a href="<?= url("request-status"); ?>" class='sidebar-link'>
            <i class="bi bi-asterisk"></i>
            <span>Order status</span>
          </a>
        </li>
        <li class="sidebar-item  ">
          <a href="<?= url("assists-tax-rates"); ?>" class='sidebar-link'>
            <i class="bi bi-collection-fill"></i>
            <span>Tax Rates</span>
          </a>
        </li>
        <?php endif; ?>

        <li class="sidebar-item  ">
          <a href="<?= url("logout"); ?>" class='sidebar-link'>
            <i class="bi bi-power"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
  </div>
</div>
<?php $v->end(); ?>