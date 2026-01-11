<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2 class="mb-3"><?= isset($description) ? '✏️ Edit' : '➕ Add' ?> Payment Description</h2>
  <form description="post" action="<?= isset($description) ? site_url('payment_descriptions/update/'.$description['id']) : site_url('payment_descriptions/store') ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">Description Name</label>
      <input type="text" name="description" class="form-control" value="<?= $description['description'] ?? '' ?>" required>
    </div>
    <button class="btn btn-primary">Save</button>
    <a href="<?= site_url('payment_descriptions') ?>" class="btn btn-secondary">Back</a>
  </form>
</div>
<?= $this->endSection() ?>
