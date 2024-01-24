<?php if ($itens) : ?>
    <div class="table-responsive-w-100">
        <table id="tabela" class="table table-striped mb-0">
            <thead>
                <tr>
                    <th class="">Variety</th>
                    <th class="">Product</th>
                    <th class="">Packaging</th>
                    <th class="">Quantity in MX</th>
                    <th class="">Price per TPC</th>
                    <th class="">Net value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item) : ?>
                    <tr>
                        <td class="text-bold-500">
                            <?php foreach ($products as $product) : ?>
                                <?php if ($item->id_product == $product->id) : ?>
                                    <?php foreach ($variety as $var) : ?>
                                        <?php if ($var->id == $product->id_variety) : ?>
                                            <?= $var->name; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-bold-500">
                            <?php foreach ($products as $product) : ?>
                                <?php if ($item->id_product == $product->id) : ?>
                                    <?= $product->name; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-bold-500">
                            <?php foreach ($stock as $stoc) : ?>
                                <?php if ($item->id_stock == $stoc->id) : ?>
                                    <?php foreach ($package as $pack) : ?>
                                        <?php if ($pack->id == $stoc->id_package) : ?>
                                            <?= $pack->name; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-bold-500"><?= $item->quantity; ?></td>
                        <td class="text-bold-500">R$ <?= $item->price; ?></td>
                        <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>

    <div class="alert alert-secondary text-center">Sem produtos para este pedido!</div>
<?php endif; ?>