<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2 class="mb-3"><?= isset($method) ? '✏️ Edit' : '➕ Add' ?> Payment Method</h2>
  <form method="post" action="<?= isset($method) ? site_url('payment_methods/update/'.$method['id']) : site_url('payment_methods/store') ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">Method Name</label>
      <input type="text" name="method" class="form-control" value="<?= $method['method'] ?? '' ?>" required>
    </div>
    <button class="btn btn-primary">Save</button>
    <a href="<?= site_url('payment_methods') ?>" class="btn btn-secondary">Back</a>
  </form>
</div>
<?= $this->endSection() ?>
