<!--<footer class="main-footer">-->
  <?php
    $userModel = new \App\Models\UserModel();
    $session = session();
    $currentUserId = $session->get('user_id');
    $users = $userModel->getAllUsersExcept($currentUserId);
?>

<script>
    function checkSession() {
        fetch('/session/check')
            .then(response => response.json())
            .then(data => {
                if (!data.logged_in) {
                    // Redirect to login if session is expired
                    window.location.href = '/auth/logout';
                }
            })
            .catch(err => {
                console.error('Session check failed', err);
            });
    }

    // Check every 1 minute (60000 ms)
    setInterval(checkSession, 60000);
</script>

<footer style="font-family: Arial, sans-serif; font-size: 14px; color: #555; background-color: #f9f9f9; padding: 20px; text-align: center;">

  <!-- Inner container for two columns -->
  <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: flex-start; border-top: 0px solid #ddd; gap: 15px; padding-top: 5px;">

    <!-- Left Column: Privacy & Terms -->
    <div style="flex: 1 1 300px; text-align: right; padding-right: 15px; border-right: 1px solid #ccc; min-width: 250px;">
      By using this software, you agree to our
      <a href="<?= site_url('privacy-policy') ?>" style="color:#0073e6; text-decoration: none;">Privacy Policy</a> and
      <a href="<?= site_url('terms-and-conditions') ?>" style="color:#0073e6; text-decoration: none;">Terms & Conditions</a>.<br>
      &copy; <?= date('Y'); ?> Nairobi Metal Detectors Ltd. All rights reserved.
    </div>

    <!-- Right Column: Footer Info -->
    <div style="flex: 1 1 300px; text-align: left; padding-left: 15px; min-width: 250px;">
      <a href="https://nairobimetaldetectors.net" style="color: #0073e6; text-decoration: none;">NMD Ltd</a> |
      <a href="tel:+254" style="color: #0073e6; text-decoration: none;">07</a>,
      <a href="tel:+254" style="color: #0073e6; text-decoration: none;">07</a>,
      <a href="tel:+254" style="color: #0073e6; text-decoration: none;">07</a><br>
      <a href="mailto:info@nairobimetaldetectors.co.ke" style="color: #0073e6; text-decoration: none;">info@nairobimetaldetectors.co.ke</a> |
      <a href="https://www.nairobimetaldetectors.co.ke" target="_blank" style="color: #0073e6; text-decoration: none;">www.nairobimetaldetectors.co.ke</a>
    </div>

  </div>

</footer>


<!--</footer>-->
