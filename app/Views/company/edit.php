<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='company')
      { ?>

        <?php if ($link['can_edit']=='1')
         { ?>


<?= $this->section('content') ?>

<h2>Edit Company</h2>

<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit Company Details</h4>
            <a href="<?= site_url('company') ?>" class="btn btn-warning btn-sm">Back</a>
        </div>
        <div class="card-body">
            <form action="<?= site_url('company/update/' . $company['id']) ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="form-control" value="<?= esc($company['name']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>" <?= $branch['id'] == $company['branch_id'] ? 'selected' : '' ?>>
                                    <?= esc($branch['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="<?= esc($company['address']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="town_id" class="form-label">Town</label>
                        <select name="town_id" id="town_id" class="form-select">
                            <option value="">Select Town</option>
                            <?php foreach ($towns as $town): ?>
                                <option value="<?= $town['id'] ?>" <?= $town['id'] == $company['town_id'] ? 'selected' : '' ?>>
                                    <?= esc($town['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="county_id" class="form-label">County</label>
                        <select name="county_id" id="county_id" class="form-select">
                            <option value="">Select County</option>
                            <?php foreach ($counties as $county): ?>
                                <option value="<?= $county['id'] ?>" <?= $county['id'] == $company['county_id'] ? 'selected' : '' ?>>
                                    <?= esc($county['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="country_id" class="form-label">Country</label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= $country['id'] ?>" <?= $country['id'] == $company['country_id'] ? 'selected' : '' ?>>
                                    <?= esc($country['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?= esc($company['phone']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= esc($company['email']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="website" class="form-label">Website</label>
                        <input type="text" name="website" id="website" class="form-control" value="<?= esc($company['website']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="logo" class="form-label">Company Logo</label>
                        <?php if ($company['logo']): ?>
                            <div class="mb-2">
                                <img src="<?= base_url($company['logo']) ?>" alt="Company Logo" width="100">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="logo" id="logo" class="form-control">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success">Update Company</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
