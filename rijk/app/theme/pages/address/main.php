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
                                    <?php if ($itens) : ?>
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th class="">Address 1</th>
                                                    <th class="">Address 2</th>
                                                    <th class="">zipcode</th>
                                                    <th class="">City</th>
                                                    <th class="">State</th>
                                                    <th class="">Country</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($itens as $item) : ?>
                                                    <tr>
                                                        <td class="text-bold-500"><?= $item->type; ?></td>
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

                                                    </tr>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php $v->start("style"); ?>
<link href="<?= url("theme/assets/css/table-sortable.css"); ?>" rel="stylesheet" media="screen">
<?php $v->end(); ?>
<?php $v->start("script"); ?>
<script type="text/javascript" src="<?= url("theme/assets/js/table-sortable.js"); ?>" charset="UTF-8"></script>
<script src="<?= url("theme/assets/js/pages/addressCity.js"); ?>"></script>
<?php $v->end(); ?>
<?php include __DIR__ . "/../../template/modal.php"; ?>
<?php include __DIR__ . "/../../template/footer.php"; ?>