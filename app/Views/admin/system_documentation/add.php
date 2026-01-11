<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2><?= isset($doc) ? 'Edit' : 'Add' ?> Document</h2>

    <form action="<?= isset($doc) ? site_url('admin/system_documentation/update/'.$doc['id']) : site_url('admin/system_documentation/store') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= isset($doc) ? esc($doc['title']) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label>File (PDF or Word)</label>
            <input type="file" name="file" class="form-control" <?= isset($doc) ? '' : 'required' ?>>
            <?php if(isset($doc)): ?>
                <small>Current file: <?= esc($doc['file_name']) ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success"><?= isset($doc) ? 'Update' : 'Save' ?></button>
        <a href="<?= site_url('admin/system_documentation') ?>" class="btn btn-secondary">Back</a>
    </form>
</div>

<?= $this->endSection() ?>
