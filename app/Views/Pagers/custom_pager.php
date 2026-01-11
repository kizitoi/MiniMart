<?php

// Custom pagination template

?>
<ul class="pagination">
    <?php if ($pager->hasPreviousPage()): ?>
        <li class="page-item"><a class="page-link" href="<?= $pager->previous() ?>">Previous</a></li>
    <?php endif; ?>

    <?php foreach ($pager->links() as $link): ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
            <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
        </li>
    <?php endforeach; ?>

    <?php if ($pager->hasNextPage()): ?>
        <li class="page-item"><a class="page-link" href="<?= $pager->next() ?>">Next</a></li>
    <?php endif; ?>
</ul>
