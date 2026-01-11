<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Officer Details</h1>
                <p>Welcome, <?= esc($username) ?>!</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?= site_url('logout') ?>" class="btn btn-danger">Logout</a>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#profileModal">
                    <img src="<?= esc($profile_image) ?>" alt="Profile Image" class="rounded-circle" width="30">
                </button>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-graduate"></i> <?
  if(isset($officer))
  {
  echo $officer['first_name']." ". $officer['last_name'] ;
  }?></h2>
            <div>
                <a href="<?= site_url('officer/officer/') ?>" class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i> Close</a>


                     </div>
        </div>




        <!-- Your content here -->

<div class="container mt-5">
  <!--  <h2>Officer Details</h2>-->
    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('officerdetails/save') ?>" method="post" enctype="multipart/form-data">


              <div class="form-row">

               <div>
              <!-- Display the uploaded photo -->
        <?php if (!empty($officer['photo'])): ?>
            <div class="form-group">
                <label>Current Photo</label>
                <div>
                    <img src="<?= base_url('uploads/officer_images/' . $officer['photo']); ?>" alt="Officer Photo" style="max-width: 150px; max-height: 150px;">
                </div>
            </div>
        <?php endif; ?>

        <!-- Upload new photo -->
        <div class="form-group">
            <label for="photo">Upload New Photo</label>
            <input type="file" class="form-control" name="photo" id="photo">
        </div>

                </div>

                   <div class="form-group col-md-6">
                <label for="admission_number">Admission Number</label>
                <input type="text" class="form-control" id="admission_number" name="admission_number" value="<?= isset($officer) ? $officer['admission_number'] : '' ?>" required>
            </div>

        </div>




      <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($officer) ? $officer['first_name'] : '' ?>" required>




        </div>
            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= isset($officer) ? $officer['last_name'] : '' ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="hostel_id">Hostel</label>
            <select id="hostel_id" name="hostel_id" class="form-control" required>
                <option value="">Choose...</option>
                <?php foreach($hostels as $hostel): ?>
                    <option value="<?= $hostel['hostel_id'] ?>" <?= isset($officer) && $officer['hostel_id'] == $hostel['hostel_id'] ? 'selected' : '' ?>>
                        <?= $hostel['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="room_number">Room Number</label>
                <input type="text" class="form-control" id="room_number" name="room_number" value="<?= isset($officer) ? $officer['room_number'] : '' ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="floor">Floor</label>
                <input type="text" class="form-control" id="floor" name="floor" value="<?= isset($officer) ? $officer['floor'] : '' ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="year_of_study">Year of Study</label>
                <input type="text" class="form-control" id="year_of_study" name="year_of_study" value="<?= isset($officer) ? $officer['year_of_study'] : '' ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="campus_name">Campus Name</label>
                <input type="text" class="form-control" id="campus_name" name="campus_name" value="<?= isset($officer) ? $officer['campus_name'] : '' ?>" required>
            </div>
            <div class="form-group col-md6">
                <label for="course">Course</label>
                <input type="text" class="form-control" id="course" name="course" value="<?= isset($officer) ? $officer['course'] : '' ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="">Choose...</option>
                    <option value="Male" <?= isset($officer) && $officer['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= isset($officer) && $officer['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="contact_mobile">Contact Mobile</label>
                <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" value="<?= isset($officer) ? $officer['contact_mobile'] : '' ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="contact_email">Contact Email</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= isset($officer) ? $officer['contact_email'] : '' ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="officer_age">Officer Age</label>
            <input type="number" class="form-control" id="officer_age" name="officer_age" value="<?= isset($officer) ? $officer['officer_age'] : '' ?>" required>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= isset($officer) ? $officer['start_date'] : '' ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= isset($officer) ? $officer['end_date'] : '' ?>" required>
            </div>
        </div>
        <!--<div class="form-group">
            <label for="photo">Upload Officer Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
        </div>-->
        <h4>Personal Items</h4>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="laptop_make">Laptop Make</label>
                <input type="text" class="form-control" id="laptop_make" name="laptop_make" value="<?= isset($officer) ? $officer['laptop_make'] : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="laptop_model">Laptop Model</label>
                <input type="text" class="form-control" id="laptop_model" name="laptop_model" value="<?= isset($officer) ? $officer['laptop_model'] : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="laptop_serial_number">Laptop Serial Number</label>
                <input type="text" class="form-control" id="laptop_serial_number" name="laptop_serial_number" value="<?= isset($officer) ? $officer['laptop_serial_number'] : '' ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="mobile_make">Mobile Phone Make</label>
                <input type="text" class="form-control" id="mobile_make" name="mobile_make" value="<?= isset($officer) ? $officer['mobile_make'] : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="mobile_imei_number">Mobile Phone IMEI Number</label>
                <input type="text" class="form-control" id="mobile_imei_number" name="mobile_imei_number" value="<?= isset($officer) ? $officer['mobile_imei_number'] : '' ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="camera_make">Camera Make</label>
                <input type="text" class="form-control" id="camera_make" name="camera_make" value="<?= isset($officer) ? $officer['camera_make'] : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="camera_model">Camera Model</label>
                <input type="text" class="form-control" id="camera_model" name="camera_model" value="<?= isset($officer) ? $officer['camera_model'] : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="camera_serial_number">Camera Serial Number</label>
                <input type="text" class="form-control" id="camera_serial_number" name="camera_serial_number" value="<?= isset($officer) ? $officer['camera_serial_number'] : '' ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="ironing_box" name="ironing_box" <?= isset($officer) && $officer['ironing_box'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="ironing_box">
                    Ironing Box Available
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="electric_kettle" name="electric_kettle" <?= isset($officer) && $officer['electric_kettle'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="electric_kettle">
                    Electric Kettle Available
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Details</button>
    </form>
</div>

    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img src="<?= esc($profile_image) ?>" alt="Profile Image" class="rounded-circle mb-3" width="100">
        <h4><?= esc($username) ?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
