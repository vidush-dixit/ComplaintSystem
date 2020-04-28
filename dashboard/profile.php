<?php
  session_start();
  include_once('../config/db.php');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card card-profile">
        <form method="post" class="md-form" id="userProfile-form" enctype="multipart/form-data">
          <div class="card-header card-header-primary">
            <div class="card-avatar">
              <?php
                $sql = "SELECT * FROM `users` WHERE `id`='".$_SESSION['userId']."'";
                $result = mysqli_query ($conn, $sql);
                {
                  $row = mysqli_fetch_assoc($result);
              ?>
              <img class="card-img" src="../assets/img/faces/<?php echo $row['profile_picture'];?>" />
              <div class="card-profile-overlay d-none">
                <span class="material-icons">add_a_photo</span>
              </div>
            </div>
          </div>
          <div class="card-body">
            <input type="hidden" name="action">
            <input type="hidden" name="updateProfileIdentity">
            <!-- Profile Picture Info -->
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <input type="file" name="profileImage" accept="image/*" disabled>
                </div>
              </div>
            </div>
            <!-- Personal Info-->
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Name</label>
                  <input type="text" name="profileName" class="form-control" value="<?php echo $row['name'];?>" required disabled>
                </div>
              </div>
            </div>
            <!-- Contact Info -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Email</label>
                  <input type="email" name="profileEmail" class="form-control" value="<?php echo $row['email'];?>" required disabled>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Phone</label>
                  <input type="text" name="profilePhone" class="form-control" value="<?php echo $row['phone'];?>" required disabled>
                </div>
              </div>
            </div>
            <!-- Department Info -->
            <?php
                if( $row['role'] != 'user' )
                {
            ?>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Designation</label>
                  <input type="text" name="profileRole" class="form-control" value="<?php echo $row['role'];?>" required disabled>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Department</label>
                  <input type="text" name="profileDepartment" class="form-control" value="<?php echo $row['dept_id'];?>" disabled>
                </div>
              </div>
            </div>
            <?php
                }
                else
                {
            ?>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Designation</label>
                  <input type="text" name="profileRole" class="form-control" value="<?php echo $row['role'];?>" disabled>
                </div>
              </div>
            </div>
            <?php
                }
              }
              // Free result set
              mysqli_free_result($result);
            ?>
            <div class="clearfix"></div>
          </div>
          <!-- Buttons - Footer -->
          <div class="card-footer pb-2">
            <div class="row w-100 text-center">
              <div class="col-12 col-md-6">
                <button type="button" class="btn btn-primary btn-round"><span class="material-icons">create</span>&nbsp;Edit Profile</button>
                <button type="submit" class="btn btn-info btn-round d-none" disabled>Update Profile</button>
              </div>
              <div class="col-12 col-md-6">
                <button type="button" class="btn btn-info btn-round" data-toggle="modal" data-target="#updatePasswordModal"><span class="material-icons">vpn_keys</span>&nbsp;Change Password</button>
                <button type="button" class="btn btn-warning btn-round d-none">Cancel Changes</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Profile Card -->

<!-- Modals -->
<!-- Change Password Modal -->
<div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="card-title">Change Password</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body md-form" id="updateProfilePassword-form">
        <div class="modal-body">
          <input type="hidden" name="action">
          <input type="hidden" name="updateProfileIdentity">
          <!-- Old Password Info-->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Old Password</label>
                <input type="password" name="profileOldPassword" class="form-control" required autocomplete="off">
              </div>
            </div>
          </div>
          <!-- New Password Info -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">New Password</label>
                <input type="password" name="profileNewPassword" class="form-control" required autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Confirm Password</label>
                <input type="password" name="profileRepeatPassword" class="form-control" required autocomplete="off">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-round">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Change Password Modal -->

<!-- Changes Confirmation Modal -->
<div class="modal fade" id="userConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="card-title">Update Confirmation</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="userConfirmation">
        <div class="modal-body">
          <p class="text-white">Enter Password to Continue</p>
          <!-- User Password -->
          <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="bmd-label-floating">Password</label>
                  <input type="password" name="currentUserPassword" class="form-control" required autocomplete="off">
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-round">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Confirmation Modal-->

<script>
  // Toggle Department Select Menu
  $(document).ready(function(){
    // Edit Profile Toggle
    $('#userProfile-form button:contains("Edit Profile")').click(function(){
      $(this).addClass('d-none');$('#userProfile-form button:contains("Change Password")').addClass('d-none');
      $('#userProfile-form button:contains("Update Profile")').removeClass('d-none').attr('disabled',false);$('#userProfile-form button:contains("Cancel Changes")').removeClass('d-none');
      $('#userProfile-form .card-avatar div.card-profile-overlay').removeClass('d-none');

      // Form fields Toggle
      $('#userProfile-form input[name="profileName"],#userProfile-form input[name="profilePhone"],#userProfile-form input[name="profileImage"]').attr('disabled',false);
    });
    // Cancel Changes Toggle
    $('#userProfile-form button:contains("Cancel Changes")').click(function(){
      $(this).addClass('d-none');$('#userProfile-form button:contains("Update Profile")').addClass('d-none').attr('disabled',true);
      $('#userProfile-form button:contains("Edit Profile")').removeClass('d-none');$('#userProfile-form button:contains("Change Password")').removeClass('d-none');
      $('#userProfile-form .card-avatar div.card-profile-overlay').addClass('d-none');

      // Form fields Toggle
      $('#userProfile-form input[name="profileName"],#userProfile-form input[name="profilePhone"],#userProfile-form input[name="profileImage"]').attr('disabled',true);
      $('#userProfile-form')[0].reset();
    });
    // Open File Dialog on profile icon click
    $('#userProfile-form .card-avatar div.card-profile-overlay').on('click', function() {
      $('#userProfile-form input[name="profileImage"]').trigger('click');
    });

    // Update User Form Request
    $('#userProfile-form button[type="submit"]').click(function(e){
      e.preventDefault();
      $('#userProfile-form input[name="action"]').val('update');$('#userProfile-form input[name="updateProfileIdentity"]').val(<?php echo $_SESSION['userId']?>);
      
      var form = $('#userProfile-form');
      var formData = new FormData(form[0]);

      $('#userProfile-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/updateProfile.inc.php',
        data : formData,
        dataType : 'json',
        contentType : false,
        cache : false,
        processData : false,
        success : function(data){
          $('#userProfile-form button[type="submit"]').html('Updating ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' || key == 'profileImage' )
              {
                md.showNotification('top', 'right', 'warning', value);
              }
              else
              {
                $("#userProfile-form input[name="+key+"]").addClass('is-invalid')
                .after('<div class="invalid-feedback">'+value+'</div>');
              }
            });
          }
          else
          {
            // alert('success');
            md.showNotification('top', 'right', 'success', 'Profile Updated successfully.');
            setTimeout(function(){location.reload();},3500);
          }
          $('#userProfile-form button[type="submit"]').html('Update Profile').removeAttr('disabled');
        },
        error: function(){md.showNotification('top', 'right', 'danger', 'Something went Wrong! Try Again');}
      });
    });
    // End Update User Form Request

    // Update Password Form Request
    $('#updateProfilePassword-form button[type="submit"]').click(function(e){
      e.preventDefault();

      $('#updateProfilePassword-form input[name="action"]').val('changePassword');$('#updateProfilePassword-form input[name="updateProfileIdentity"]').val(<?php echo $_SESSION['userId']?>);
      $('#updateProfilePassword-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/updateProfile.inc.php',
        data : $('#updateProfilePassword-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#updateProfilePassword-form button[type="submit"]').html('Updating ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#updateProfilePassword-form .modal-footer").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                $("#updateProfilePassword-form input[name="+key+"]").addClass('is-invalid')
                .after('<div class="invalid-feedback">'+value+'</div>');
              }
            });
          }
          else
          {
            // alert('success');
            $("#updateProfilePassword-form .modal-footer").before('<div class="alert alert-success">Password Updated successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#updateProfilePassword-form button[type="submit"]').html('Update Password').removeAttr('disabled');
        },
        error: function(){$("#updateProfilePassword-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
      });
    });
    // End Update Password Form Request
  });
</script>