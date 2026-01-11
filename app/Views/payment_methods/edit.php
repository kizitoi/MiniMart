<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2 class="mb-3">✏️ Edit Payment Method</h2>

  <form method="post" action="<?= site_url('payment_methods/update/'.$method['id']) ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label">Method Name</label>
      <input
        type="text"
        name="method"
        class="form-control"
        value="<?= esc($method['method']) ?>"
        required>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="<?= site_url('payment_methods') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
