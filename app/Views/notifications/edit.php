<?= $this->extend('layout') ?>


<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='notifications')
      { ?>


<?= $this->section('content') ?>



<div class="container mt-4">
<!--  <pre><?php //print_r($user); ?></pre>-->

    <h3>Notification Preferences for <?= esc($user['name']) ?>  (<?= esc($user['username'])?>)</h3>
  <br>
    <hr>

    <form method="post" action="<?= site_url('notifications/update/' . $user['user_id']) ?>">
        <div class="row">
            <!-- EMAIL NOTIFICATIONS COLUMN -->
            <div class="col-md-6">
                <h5><b>EMAIL NOTIFICATIONS TO <?= esc($user['email']) ?></b></h5>
                <hr>
                <?php
                $emailFields = [

                    'new_user_registration' => 'New User Registration',
                    'low_stock' => 'Low Stock',
                    'item_sale' => 'Item Sale',
                ];
                foreach ($emailFields as $key => $label): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="<?= $key ?>" <?= $notification[$key] ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $label ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- MOBILE NOTIFICATIONS COLUMN -->
            <div class="col-md-6">
                <h5><b>MOBILE SMS NOTIFICATIONS TO <?= esc($user['mobile']) ?></b></h5>
                <hr>
                <?php
                $smsFields = [

                    'new_user_registration_sms' => 'New User Registration (SMS)',
                    'low_stock_sms' => 'Low Stock (SMS)',
                    'item_sale_sms' => 'Item Sale (SMS)',
                ];
                foreach ($smsFields as $key => $label): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="<?= $key ?>" <?= $notification[$key] ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $label ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Save Preferences</button>
            <a href="<?= site_url('users') ?>" class="btn btn-secondary">Back to Users</a>
        </div>
    </form>


</div>

<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
