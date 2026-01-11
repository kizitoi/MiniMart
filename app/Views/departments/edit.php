<?= $this->extend('layout') ?>


<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='departments')
      { ?>

        <?php if ($link['can_edit']=='1')
         { ?>

<?= $this->section('content') ?>

<div class="container">
    <h2>Edit Department</h2>
    <form action="<?= site_url('departments/update/'.$department['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Department Name</label>
            <input type="text" name="name" class="form-control" required value="<?= esc($department['name']) ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required><?= esc($department['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('departments') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
<?php } ?>
<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
