<?php if ($order) : ?>


    Cliente: <?= $user->name; ?><br />
    Email: <?= $user->email; ?><br />
    Telefone: <?= $user->phone; ?><br />

    <hr>
    Vendedor: <?= $salesman->name; ?><br />
    Email: <?= $salesman->email; ?><br />
    Telefone: <?= $salesman->phone; ?><br />
    <hr>

    Desconto: <?= $order->discount; ?>%<br />
    Número pedido: <?= $order->order_number; ?><br />

    Data do pedido: <?= date('d/m/Y', strtotime($order->order_date)); ?><br />

    Status do Pedido : <span class="text-danger"><?php foreach ($status as $stat) : ?>
            <?php if ($order->status == $stat->id) : ?>
                <?= $stat->name; ?>
            <?php endif; ?>
        <?php endforeach; ?></span>

    <hr>


    <div class="table-responsive-w-100">
        <table id="tabela" class="table table-striped mb-0">
            <thead>
                <tr>
                    <th class="">Variety</th>
                    <th class="">Product</th>
                    <th class="">Packaging</th>
                    <th class="">Quantity in MX</th>
                    <th class="">Price per MX</th>
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

<?php endif; ?>