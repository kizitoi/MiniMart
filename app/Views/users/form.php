<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='users')
      { ?>

        <?php if ($link['can_edit']=='1')
         { ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="mb-0">
                        <i class="fa <?= isset($user) ? 'fa-user-edit' : 'fa-user-plus' ?>"></i>
                        <?= isset($user) ? 'Edit User' : 'Add New User' ?>
                    </h5>
                    <a href="<?= site_url('users') ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to Users
                    </a>
                </div>

                <div class="card-body">
                    <form action="<?= site_url('users/save') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="user_id" value="<?= isset($user) ? $user['user_id'] : '' ?>">

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" value="<?= isset($user) ? esc($user['username']) : '' ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= isset($user) ? esc($user['email']) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select" name="role_id" id="role_id" required>
                                <option value="">-- Select Role --</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>" <?= isset($user) && $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                        <?= esc($role['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="shop_id" class="form-label">Shop</label>
                            <select class="form-select" name="shop_id" id="shop_id" required>
                                <option value="">-- Select Shop --</option>
                                <?php foreach ($shops as $shop): ?>
                                    <option value="<?= $shop['id'] ?>" <?= isset($user) && $user['shop_id'] == $shop['id'] ? 'selected' : '' ?>>
                                        <?= esc($shop['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <?= isset($user) ? '(Leave blank to keep current)' : '' ?></label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="******" <?= !isset($user) ? 'required' : '' ?>>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" <?= !isset($user) ? 'required' : '' ?>>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa <?= isset($user) ? 'fa-save' : 'fa-user-plus' ?>"></i>
                                <?= isset($user) ? 'Update User' : 'Add User' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
