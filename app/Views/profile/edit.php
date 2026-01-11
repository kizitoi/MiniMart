<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Update Profile</h4>
                </div>
                <div class="card-body">

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" value="<?= esc($user['username']) ?>" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="<?= esc($user['email']) ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" value="<?= esc($user['mobile']) ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="<?= esc($user['name']) ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" value="<?= esc($user['designation']) ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control">

                        <?php if (!empty($user['profile_image'])): ?>
                            <small class="d-block mt-1">
                                Current: <img src="<?= base_url('view-image/' . esc($user['user_id']) . '/profile') ?>" width="50" class="img-thumbnail">
                            </small>
                        <?php endif; ?>


                        </div>

                        <div class="mb-3">
                            <label class="form-label">Signature</label>
                            <input type="file" name="signature" class="form-control">
                            <?php if (!empty($user['signature_link'])): ?>
                                <small class="d-block mt-1">
                                    Current: <img src="<?= base_url('view-image/' . esc($user['user_id']) . '/signature') ?>" width="100" class="img-thumbnail">
                                </small>
                            <?php endif; ?>
                        </div>











                        <button type="submit" class="btn btn-success w-100">✅ Update Profile</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Change Password</h4>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('profile/change-password') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">🔒 Change Password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
