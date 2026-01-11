<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">POS List</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Shop</th>
                <th>Item No</th>
                <th>Category</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Reorder Level</th>
                <th>Photo</th>
                <th>Vatable</th>
                <th>VAT Code</th>
                <th>Selling Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pos_list as $entry): ?>
                <tr>
                    <td><?= esc($entry['shop_name']) ?></td>
                    <td><?= esc($entry['item_no']) ?></td>
                    <td><?= esc($entry['category_name']) ?></td>
                    <td><?= esc($entry['item_name']) ?></td>
                    <td><?= esc($entry['description']) ?></td>
                    <td><?= esc($entry['quantity']) ?></td>
                    <td><?= esc($entry['reorder_level_quantity']) ?></td>
                    <td>
                        <?php if (!empty($entry['photo'])): ?>
                            <img src="<?= base_url('/images/items/' . $entry['photo']) ?>" alt="Item Photo" style="width: 50px;">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                    </td>
                    <td><?= $entry['vatable'] ? 'Yes' : 'No' ?></td>
                    <td><?= esc($entry['vat_code']) ?></td>
                    <td><?= number_format($entry['selling_price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
