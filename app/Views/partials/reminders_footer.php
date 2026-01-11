<!-- Container for bottom-right toasts -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
  <div id="reminderToastContainer"></div>
</div>

<script>
(function() {
  // Fetch today's reminders for current user
  fetch("<?= site_url('reminders/today') ?>", { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
    .then(r => r.json())
    .then(({ok, data}) => {
      if (!ok || !data || !data.length) return;

      // Create Bootstrap toasts
      data.forEach(function(r) {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
          <div class="toast align-items-center text-bg-dark border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="8000">
            <div class="d-flex">
              <div class="toast-body">
                <strong>${escapeHtml(r.title ?? 'Reminder')}</strong><br>
                <small>${(r.remind_at ?? '').replace(' ', ' • ')}</small>
                ${r.about ? `<div class="mt-1 small">${escapeHtml(r.about)}</div>` : ''}
                <div class="mt-2">
                  <a href="<?= site_url('reminders') ?>" class="btn btn-sm btn-primary">Open</a>
                </div>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>`;
        document.getElementById('reminderToastContainer').appendChild(wrapper.firstElementChild);
      });

      // Show them
      const toasts = document.querySelectorAll('#reminderToastContainer .toast');
      toasts.forEach(t => new bootstrap.Toast(t).show());
    })
    .catch(() => {});
})();

function escapeHtml(s) {
  return String(s).replace(/[&<>"'`=\/]/g, function (m) {
    return ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    })[m];
  });
}
</script>
