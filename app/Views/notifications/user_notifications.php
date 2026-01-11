<?= $this->extend('layout') ?>


<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='notifications')
      { ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <h3>Notification Settings for <?= esc($user['username']) ?></h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url("notifications/update/" . $user['user_id']) ?>">
        <div class="card shadow-sm p-4 mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_incident" <?= $settings['new_incident'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Incident</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="incident_update" <?= $settings['incident_update'] ? 'checked' : '' ?>>
                <label class="form-check-label">Incident Update</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_remarks" <?= $settings['new_remarks'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Remarks</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remarks_update" <?= $settings['remarks_update'] ? 'checked' : '' ?>>
                <label class="form-check-label">Remarks Update</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_action" <?= $settings['new_action'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Action Taken</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="action_update" <?= $settings['action_update'] ? 'checked' : '' ?>>
                <label class="form-check-label">Action Taken Update</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_conclusion" <?= $settings['new_conclusion'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Conclusion</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="conclusion_update" <?= $settings['conclusion_update'] ? 'checked' : '' ?>>
                <label class="form-check-label">Conclusion Update</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_finding" <?= $settings['new_finding'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Finding</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="finding_update" <?= $settings['finding_update'] ? 'checked' : '' ?>>
                <label class="form-check-label">Finding Update</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_user_registration" <?= $settings['new_user_registration'] ? 'checked' : '' ?>>
                <label class="form-check-label">New User Registration</label>
            </div>



            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_incident_sms" <?= $settings['new_incident_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Incident SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="incident_update_sms" <?= $settings['incident_update_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">Incident Update SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_remarks_sms" <?= $settings['new_remarks_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Remarks SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remarks_update_sms" <?= $settings['remarks_update_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">Remarks Update SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_action_sms" <?= $settings['new_action_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Action Taken SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="action_update_sms" <?= $settings['action_update_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">Action Taken Update SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_conclusion_sms" <?= $settings['new_conclusion_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Conclusion SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="conclusion_update_sms" <?= $settings['conclusion_update_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">Conclusion Update SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_finding_sms" <?= $settings['new_finding_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">New Finding SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="finding_update_sms" <?= $settings['finding_update_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">Finding Update SMS</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_user_registration_sms" <?= $settings['new_user_registration_sms'] ? 'checked' : '' ?>>
                <label class="form-check-label">New User Registration SMS</label>
            </div>





            <button class="btn btn-success mt-3">Save Settings</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
