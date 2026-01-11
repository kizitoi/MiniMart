<?php if ($pager->hasPreviousPage() || $pager->hasNextPage()): ?>
    <ul class="pagination">
        <?php if ($pager->hasPreviousPage()): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">
                    &laquo; Previous
                </a>
            </li>
        <?php else: ?>
            <li class="page-itemdisabled">
                <span class="page-link">&laquo; Previous</span>
            </li>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if ($pager->hasNextPage()): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Next">
                    Next &raquo;
                </a>
            </li>
        <?php else: ?>
            <li class="page-itemdisabled">
                <span class="page-link">Next &raquo;</span>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>

<script>
  .pagination {
    display: flex;
    list-style: none;
    padding: 0;
}

.pagination.page-item {
    margin: 05px;
}

.pagination.page-link {
    padding: 8px12px;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #007bff;
}

.pagination.page-item.active.page-link {
    background-color:#007bff;
    color: white;
}

.pagination.page-item.disabled.page-link {
    color:#6c757d;
    border-color:#ddd;
}

</script>
