<?= $this->extend('layout') ?>


<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='departments')
      { ?>

        <?php if ($link['can_add']=='1')
         { ?>
<?= $this->section('content') ?>

<div class="container">
    <h2>Add Department</h2>
    <form action="<?= site_url('departments/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Department Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= site_url('departments') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
