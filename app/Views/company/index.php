
<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='company')
      { ?>

  <?= $this->section('content') ?>
  <div class="container mt-4">
      <h2 class="mb-4">Company Details</h2>

      <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

<!--
      <form action="<? //= site_url('company/save') ?>" method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label for="company_name" class="form-label">Company Name</label>
                      <input type="text" name="company_name" class="form-control" required>
                  </div>

                  <div class="col-md-6">
                      <label for="branch_id" class="form-label">Branch</label>
                      <select name="branch_id" class="form-select" required>
                          <option value="">Select Branch</option>
                          <?php foreach ($branches as $branch): ?>
                              <option value="<?= esc($branch['id']) ?>"><?= esc($branch['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
              </div>

              <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" name="address" class="form-control" required>
              </div>

              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="town_id" class="form-label">Town</label>
                      <select name="town_id" class="form-select" required>
                          <option value="">Select Town</option>
                          <?php foreach ($towns as $town): ?>
                              <option value="<?= esc($town['id']) ?>"><?= esc($town['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>

                  <div class="col-md-4">
                      <label for="county_id" class="form-label">County</label>
                      <select name="county_id" class="form-select" required>
                          <option value="">Select County</option>
                          <?php foreach ($counties as $county): ?>
                              <option value="<?= esc($county['id']) ?>"><?= esc($county['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>

                  <div class="col-md-4">
                      <label for="country_id" class="form-label">Country</label>
                      <select name="country_id" class="form-select" required>
                          <option value="">Select Country</option>
                          <?php foreach ($countries as $country): ?>
                              <option value="<?= esc($country['id']) ?>"><?= esc($country['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
              </div>

              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="phone_number" class="form-label">Phone Number</label>
                      <input type="text" name="phone_number" class="form-control" required>
                  </div>

                  <div class="col-md-4">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" required>
                  </div>

                  <div class="col-md-4">
                      <label for="website" class="form-label">Website</label>
                      <input type="url" name="website" class="form-control">
                  </div>
              </div>

              <div class="mb-3">
                  <label for="logo" class="form-label">Upload Logo</label>
                  <input type="file" name="logo" class="form-control">
              </div>

              <button type="submit" class="btn btn-primary">Save Company Details</button>
          </form>
-->

      <?php if (!empty($companies)): ?>
        <!--  <h4 class="mb-3">Company</h4>-->
          <div class="table-responsive">
              <table class="table table-striped table-bordered align-middle">
                  <thead class="table-dark">
                      <tr>
                          <th>#</th>
                          <th>Logo</th>
                          <th>Company Name</th>
                          <th>Branch</th>
                          <th>Address</th>
                          <th>Town</th>
                          <th>County</th>
                          <th>Country</th>
                          <th>Phone</th>
                          <th>Email</th>
                          <th>Website</th>
                          <th>Actions</th>

                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($companies as $index => $company): ?>
                          <tr>
                              <td><?= $index + 1 ?></td>
                              <td>
                                  <?php if ($company['logo']): ?>
                                      <img src="<?= base_url($company['logo']) ?>" alt="Logo" width="60" height="60">
                                  <?php else: ?>
                                      <span class="text-muted">No Logo</span>
                                  <?php endif; ?>
                              </td>
                              <td><?= esc($company['name']) ?></td>
                              <td><?= esc($company['branch_name']) ?></td>
                              <td><?= esc($company['address']) ?></td>
                              <td><?= esc($company['town_name']) ?></td>
                              <td><?= esc($company['county_name']) ?></td>
                              <td><?= esc($company['country_name']) ?></td>
                              <td><?= esc($company['phone']) ?></td>
                              <td><?= esc($company['email']) ?></td>
                              <td><a href="<?= esc($company['website']) ?>" target="_blank"><?= esc($company['website']) ?></a></td>

                              <td>
  <a href="<?= site_url('company/edit/' . $company['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
  <form action="<?= site_url('company/delete/' . $company['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this company?');">
  <!--  <button type="submit" class="btn btn-sm btn-danger">Delete</button>-->
  </form>
</td>

                          </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
      <?php else: ?>
          <p class="text-muted">No companies registered yet.</p>
      <?php endif; ?>
  </div>
  <?= $this->endSection() ?>

  <?php } ?>
  <?php endforeach; ?>
  <?php endif; ?>
