<?php if ($itens) : ?>
<h4 style="margin:0;">Commercial Order</h4>
<hr style="margin-top:5px; float:left; width:100%" />
<table style='width: 100%'>
  <thead>
    <tr>
      <th style="font-size:11px; text-align:left;">Variety</th>
      <th style="font-size:11px; text-align:left;">Packaging</th>
      <th style="font-size:11px; text-align:left;">Quantity in MX</th>
      <th style="font-size:11px; text-align:left;">Cash Discount</th>
      <th style="font-size:11px; text-align:left;">Price per MX after discount with ICMS</th>
      <th style="font-size:11px; text-align:left;">Net value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($itens as $item) : ?>
    <?php foreach ($products as $product) : ?>
    <?php if ($item->id_stock == $product->id) : ?>
    <tr>
      <td class="text-bold-500">
        <?php foreach ($variety as $var) : ?>
        <?php if ($var->id == $product->id_variety) : ?>
        <?= $var->name; ?>
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
      <td class="text-bold-500"><?= number_format($item->quantity, 2, ',', '.'); ?></td>


      <td class="text-bold-500">
        <?php if ($order->cash_payment) : ?>
        <?= $order->cash_payment; ?>%
        <?php else : ?>0%<?php endif; ?>
      </td>
      <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
      <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
      </td>

    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>


      <td><strong>Net value: R$
          <?= number_format($commercialOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>

    </tr>
  </tfoot>
</table>
<?php endif; ?>

<?php if ($itensBonnus) : ?>
<h4 style="margin:0;">Bonus order
</h4>
<hr style="margin-top:5px; float:left; width:100%" />
<table style='width: 100%'>
  <thead>
    <tr>
      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Variety</th>
      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Packaging</th>
      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Quantity in MX</th>
      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Cash Discount</th>
      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Price per MX after
        discount with ICMS</th>
      <th class="" <?php if ($userRoles == 4) : ?>style="color:red;" <?php endif; ?>>Net value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($itensBonnus as $item) : ?>
    <?php foreach ($products as $product) : ?>
    <?php if ($item->id_stock == $product->id) : ?>
    <tr>
      <td class="text-bold-500">
        <?php foreach ($variety as $var) : ?>
        <?php if ($var->id == $product->id_variety) : ?>
        <?= $var->name; ?>
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

      <td class="text-bold-500"><?= number_format($item->quantity, 2, ',', '.'); ?></td>
      <td class="text-bold-500">
        <?php if ($order->cash_payment) : ?>
        <?= $order->cash_payment; ?>%
        <?php else : ?>0%<?php endif; ?>
      </td>
      <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
      <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
      </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><strong>Net value: R$ <?= number_format($bonnusOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
      </td>

    </tr>
  </tfoot>
</table>
<?php endif; ?>
<?php if ($itensSalanova) : ?>
<h4 style="margin:0;">Salanova
</h4>
<hr style="margin-top:5px; float:left; width:100%" />
<table style='width: 100%'>
  <thead>
    <tr>
      <th class="">Variety</th>
      <th class="">Packaging</th>
      <th class="">Quantity in MX</th>
      <th class="">Cash Discount</th>
      <th class="">Price per MX after discount with ICMS</th>
      <th class="">Net value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($itensSalanova as $item) : ?>
    <?php foreach ($products as $product) : ?>
    <?php if ($item->id_stock == $product->id) : ?>
    <tr>
      <td class="text-bold-500">
        <?php foreach ($variety as $var) : ?>
        <?php if ($var->id == $product->id_variety) : ?>
        <?= $var->name; ?>
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
      <td class="text-bold-500"><?= number_format($item->quantity, 2, ',', '.'); ?></td>

      <td class="text-bold-500">
        <?php if ($order->cash_payment) : ?>
        <?= $order->cash_payment; ?>%
        <?php else : ?>0%<?php endif; ?>
      </td>
      <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
      <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
      </td>

    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><strong>Net value: R$
          <?= number_format($salanovaOrderAllValue->value_icms, 2, ',', '.'); ?></strong>
      </td>

    </tr>
  </tfoot>
</table>
<?php endif; ?>

<?php if ($itensAditionalDiscount) : ?>
<h4 style="margin:0;">
  Aditional Discount
</h4>
<hr style="margin-top:5px; float:left; width:100%" />
<table style='width: 100%'>
  <thead>
    <tr>
      <th class="">Variety</th>
      <th class="">Packaging</th>
      <th class="">Quantity in MX</th>
      <th class="">Cash Discount</th>
      <th class="">Price per MX after discount with ICMS</th>
      <th class="">Net value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($itensAditionalDiscount as $item) : ?>
    <?php foreach ($products as $product) : ?>
    <?php if ($item->id_stock == $product->id) : ?>
    <tr>
      <td class="text-bold-500">
        <?php foreach ($variety as $var) : ?>
        <?php if ($var->id == $product->id_variety) : ?>
        <?= $var->name; ?>
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
      <td class="text-bold-500"><?= number_format($item->quantity, 2, ',', '.'); ?></td>
      <td class="text-bold-500">
        <?php if ($order->cash_payment) : ?>
        <?= $order->cash_payment; ?>%
        <?php else : ?>0%<?php endif; ?>
      </td>
      <td class="text-bold-500">R$ <?= number_format($item->value_icms, 2, ',', '.'); ?></td>
      <td class="text-bold-500">R$ <?= number_format($item->quantity * $item->value_icms, 2, ',', '.'); ?>
      </td>

    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><strong>Net value: R$
          <?= number_format($aditionalDiscountOrderAllValue->value_icms, 2, ',', '.'); ?></strong></td>

    </tr>
  </tfoot>
</table>
<?php endif; ?>