<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
  <h2 class="text-primary mb-3">💳 Payment Methods</h2>
  <a href="<?= site_url('payment_methods/create') ?>" class="btn btn-success mb-3">➕ Add Method</a>
  <?php if(session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
  <?php endif ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif ?>
  <table class="table table-bordered table-hover">
    <thead class="table-dark"><tr><th>#</th><th>Method</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($methods as $m): ?>
        <tr>
          <td><?= $m['id'] ?></td>
          <td><?= esc($m['method']) ?></td>
          <td>
            <a href="<?= site_url('payment_methods/edit/'.$m['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="<?= site_url('payment_methods/delete/'.$m['id']) ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this method?')">Delete</a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
