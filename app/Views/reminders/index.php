<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container py-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
    <h2 class="mb-2 mb-md-0">📝 Notes & Reminders</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reminderModal" onclick="openReminderCreate()">
      <i class="fa fa-plus"></i> New Reminder
    </button>
  </div>

  <!-- Alerts -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Filters -->
  <form method="get" class="mb-3">
    <div class="row g-2">
      <div class="col-12 col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search title/about…" value="<?= esc($search ?? '') ?>">
      </div>
      <div class="col-6 col-md-3">
        <select name="status" class="form-select">
          <option value="">-- Status --</option>
          <?php foreach (['New','Pending','Completed'] as $s): ?>
            <option value="<?= $s ?>" <?= ($status ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <button class="btn btn-primary w-100"><i class="fa fa-search"></i> Search</button>
      </div>
    </div>
  </form>

  <!-- Desktop Table -->
  <div class="d-none d-md-block">
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>About</th>
            <th>Remind @ (Africa/Nairobi)</th>
            <th>Status</th>
            <th class="text-center" style="width: 160px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $i = 1; foreach ($reminders as $r): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= esc($r['title']) ?></td>
            <td><?= esc(mb_strimwidth($r['about'] ?? '', 0, 80, '…')) ?></td>
            <td><?= esc(date('Y-m-d H:i', strtotime($r['remind_at']))) ?></td>
            <td>
              <?php
                $badge = $r['status'] === 'Completed' ? 'success' : ($r['status'] === 'Pending' ? 'warning' : 'secondary');
              ?>
              <span class="badge bg-<?= $badge ?>"><?= esc($r['status']) ?></span>
            </td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#reminderModal" onclick='openReminderView(<?= json_encode($r, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>)'>View</button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reminderModal" onclick='openReminderEdit(<?= json_encode($r, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>)'>Edit</button>
                <a class="btn btn-danger" href="<?= site_url('reminders/delete/'.$r['id']) ?>" onclick="return confirm('Delete reminder?')">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Mobile Cards -->
  <div class="d-block d-md-none">
    <div class="row g-3">
      <?php $i = 1; foreach ($reminders as $r): ?>
      <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1"><?= esc($r['title']) ?></h5>
                <small class="text-muted">#<?= $i++ ?> • <?= esc(date('Y-m-d H:i', strtotime($r['remind_at']))) ?> (Nairobi)</small>
              </div>
              <?php
                $badge = $r['status'] === 'Completed' ? 'success' : ($r['status'] === 'Pending' ? 'warning' : 'secondary');
              ?>
              <span class="badge bg-<?= $badge ?>"><?= esc($r['status']) ?></span>
            </div>
            <p class="mt-2 mb-3"><?= nl2br(esc(mb_strimwidth($r['about'] ?? '', 0, 140, '…'))) ?></p>
            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reminderModal" onclick='openReminderView(<?= json_encode($r, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>)'>View</button>
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reminderModal" onclick='openReminderEdit(<?= json_encode($r, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>)'>Edit</button>
              <a class="btn btn-sm btn-danger" href="<?= site_url('reminders/delete/'.$r['id']) ?>" onclick="return confirm('Delete reminder?')">Delete</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Pagination -->
  <div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('reminders', 'default_full') ?>
  </div>
</div>

<!-- Create/Edit/View Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="reminderForm" method="post" action="<?= site_url('reminders/store') ?>">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="reminderModalLabel">New Reminder</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Reminder Title</label>
              <input type="text" name="title" id="m_title" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">About Reminder</label>
              <textarea name="about" id="m_about" rows="4" class="form-control" placeholder="Notes…"></textarea>
            </div>
            <div class="col-6">
              <label class="form-label">Remind Date (Africa/Nairobi)</label>
              <input type="date" name="remind_date" id="m_date" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label">Remind Time (24h)</label>
              <input type="time" name="remind_time" id="m_time" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Status</label>
              <select name="status" id="m_status" class="form-select">
                <option value="New">New</option>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="me-auto text-decoration-none d-none" id="m_view_only_link" target="_blank">Open details</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="m_submit_btn" class="btn btn-primary">Save</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
  // Helpers to open modal modes
  function resetModal() {
    document.getElementById('reminderForm').action = "<?= site_url('reminders/store') ?>";
    document.getElementById('reminderModalLabel').innerText = "New Reminder";
    document.getElementById('m_title').value = '';
    document.getElementById('m_about').value = '';
    document.getElementById('m_date').value = '';
    document.getElementById('m_time').value = '';
    document.getElementById('m_status').value = 'New';
    document.getElementById('m_title').readOnly = false;
    document.getElementById('m_about').readOnly = false;
    document.getElementById('m_date').readOnly = false;
    document.getElementById('m_time').readOnly = false;
    document.getElementById('m_status').disabled = false;
    document.getElementById('m_submit_btn').classList.remove('d-none');
    document.getElementById('m_view_only_link').classList.add('d-none');
  }

  function openReminderCreate() {
    resetModal();
  }

  function openReminderEdit(r) {
    resetModal();
    document.getElementById('reminderForm').action = "<?= site_url('reminders/update') ?>/" + r.id;
    document.getElementById('reminderModalLabel').innerText = "Edit Reminder";

    document.getElementById('m_title').value = r.title ?? '';
    document.getElementById('m_about').value = r.about ?? '';

    const dt = new Date(r.remind_at.replace(' ', 'T'));
    document.getElementById('m_date').value = dt.toISOString().slice(0,10);
    document.getElementById('m_time').value = dt.toTimeString().slice(0,5);

    document.getElementById('m_status').value = r.status ?? 'New';
  }

  function openReminderView(r) {
    resetModal();
    document.getElementById('reminderModalLabel').innerText = "Reminder Details";
    document.getElementById('m_title').value = r.title ?? '';
    document.getElementById('m_about').value = r.about ?? '';
    const dt = new Date(r.remind_at.replace(' ', 'T'));
    document.getElementById('m_date').value = dt.toISOString().slice(0,10);
    document.getElementById('m_time').value = dt.toTimeString().slice(0,5);
    document.getElementById('m_status').value = r.status ?? 'New';

    // set readonly for view mode
    document.getElementById('m_title').readOnly = true;
    document.getElementById('m_about').readOnly = true;
    document.getElementById('m_date').readOnly = true;
    document.getElementById('m_time').readOnly = true;
    document.getElementById('m_status').disabled = true;
    document.getElementById('m_submit_btn').classList.add('d-none');
    document.getElementById('m_view_only_link').classList.remove('d-none');
  }
</script>

<?= $this->endSection() ?>
