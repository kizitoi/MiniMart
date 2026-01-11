<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4 text-center">System Administration</h2>

    <?php if (!empty($header_links)): ?>
        <div class="row">
            <?php foreach ($header_links as $link): ?>
                <?php if ($link['isbutton'] == '1'): ?>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="<?= site_url($link['link']) ?>" class="btn btn-primary w-100 d-flex align-items-center">
                            <i class="<?= esc($link['icon']) ?> me-2"></i>
                            <span><?= esc($link['label']) ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No administration links available.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
