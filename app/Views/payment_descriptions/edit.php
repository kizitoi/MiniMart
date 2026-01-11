<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2 class="mb-3">✏️ Edit Payment Description</h2>

  <form method="post" action="<?= site_url('payment_descriptions/update/'.$description['id']) ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label">Description Name</label>
      <input
        type="text"
        name="description"
        class="form-control"
        value="<?= esc($description['description']) ?>"
        required>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="<?= site_url('payment_descriptions') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
