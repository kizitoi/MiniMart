<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='bulksms')
      { ?>



<?= $this->section('content') ?>

<div class="container mt-5">
    <h3>Bulk SMS Settings</h3>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('bulksms/save') ?>">
        <div class="mb-3">
            <label for="username" class="form-label">SMS Username</label>
            <input type="text" name="username" class="form-control" value="<?= esc($setting['username'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
      <label for="password" class="form-label">SMS Password</label>
      <div class="input-group">
          <input type="password" id="password" name="password" class="form-control" value="<?= esc($setting['password'] ?? '') ?>" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
              👁️
          </button>
      </div>
  </div>

        <div class="mb-3">
            <label for="api_url" class="form-label">API URL</label>
            <input type="text" name="api_url" class="form-control" value="<?= esc($setting['api_url'] ?? '') ?>" readonly>
        </div>
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
</div>


<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

<?= $this->endSection() ?>


<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
