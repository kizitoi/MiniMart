<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2><?= isset($perm) ? 'Edit' : 'Add' ?> Permission</h2>

    <form action="<?= isset($perm) ? site_url('permissions/update/'.$perm['id']) : site_url('permissions/store') ?>" method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= isset($perm) ? esc($perm['name']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Label</label>
            <input type="text" name="label" class="form-control" value="<?= isset($perm) ? esc($perm['label']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="text" name="link" class="form-control" value="<?= isset($perm) ? esc($perm['link']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Icon (CSS class e.g. fa fa-user)</label>
            <input type="text" name="icon" class="form-control" value="<?= isset($perm) ? esc($perm['icon']) : '' ?>" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="isbutton" class="form-check-input" <?= isset($perm) && $perm['isbutton'] ? 'checked' : '' ?>>
            <label class="form-check-label">Is Button?</label>
        </div>

        <button type="submit" class="btn btn-success"><?= isset($perm) ? 'Update' : 'Save' ?></button>
        <a href="<?= site_url('permissions') ?>" class="btn btn-secondary">Back</a>
    </form>
</div>

<?= $this->endSection() ?>
