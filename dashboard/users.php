<?php
  session_start();
  include_once('../config/db.php');
  if ( $_SESSION['userType'] == 'admin' )
  {
?>
<div class="container-fluid">
  <div class="row">

    <!-- Table Column -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
          <div class="row">
            <div class="col-12 col-xl-10 col-lg-8">
              <h4 class="card-title">Users</h4>
              <p class="card-category">All Registered Users and Caretakers</p>
            </div>
            <div class="col-12 col-xl-2 col-lg-2">
              <!-- Button trigger modal -->
              <button class="card-title btn btn-success pl-0 mt-1" data-toggle="modal" data-target="#createUserModal"><i class="material-icons pl-3 pr-2">add</i>Add Users</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="user-table">
              <thead class="text-primary text-center">
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Department</th><th>Action</th>
              </thead>
              <tbody>
                <?php
                  $sql = "SELECT users.id,users.name,users.email,users.phone,users.role,departments.name AS dept_name FROM `users` JOIN `departments` ON users.dept_id=departments.code";
                  $result = mysqli_query($conn, $sql);
                  $id = 0;

                  if (mysqli_num_rows($result) > 0)
                  {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      $id += 1;
                      echo "<tr class=\"text-center\">";
                      echo "<td class=\"d-none\">".$row['id']."</td>";
                      echo "<td>".$id."</td><td>".$row['name']."</td><td>".$row['email']."</td><td>".$row['phone']."</td><td class=\"text-primary font-weight-bold\">".$row['role'];
                      if ( $row['role'] != 'user' )
                        echo "</td><td>".$row['dept_name']."</td>";
                      else
                        echo "<td>-</td>";     
                      echo "<td><button class=\"btn btn-info btn-round btn-fab\" id=\"updateUser\" data-toggle=\"modal\" data-target=\"#updateUserModal\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Edit\">create</i></button><button class=\"btn btn-danger btn-round btn-fab\" id=\"deleteUser\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Remove\">delete_forever</i></button></td>";
                      echo "</tr>";
                    }
                  }
                  else
                  {
                    echo "<tr class=\"text-center\">";
                    echo "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";
                    echo "</tr>";   
                  }
                  // Free result set
                  mysqli_free_result($result);
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="card-title" id="exampleModalLongTitle">Create New User</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body md-form" id="createUser-form">
        <div class="modal-body">

          <!-- Name -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Full Name</label>
                <input type="text" name="createUserName" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Contact Info -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Email address</label>
                <input type="email" name="createUserEmail" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Phone</label>
                <input type="text" name="createUserPhone" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Role Info -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="role" class="bmd-label-floating">Role</label>
                <select class="form-control" name="createUserRole" required>
                  <option value="admin">Admin</option>
                  <option value="caretaker">Department Head</option>
                  <option value="user" selected>User</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Dashboard Info -->
          <div class="row d-none">
            <div class="col-md-12">
              <div class="form-group">
                <label for="department" class="bmd-label-floating">Department</label>
                <select class="form-control" name="createUserDepartment" required>
                  <?php
                    $sql = "SELECT * FROM departments";
                    $result = mysqli_query($conn, $sql);

                    if ( mysqli_num_rows($result) > 0 )
                    {
                      $count = 1;
                      while($row = mysqli_fetch_assoc($result))
                      {
                        if($count == 1)
                        {
                          echo "<option value=".$row['code']." selected>".$row['name']."</option>";
                          $count += 1;
                        }
                        else
                          echo "<option value=".$row['code'].">".$row['name']."</option>";                        
                      }
                    }
                    else
                    {
                      echo "<option value='null' selected>No Department Found!</option>";
                    }
                    // Free result set
                    mysqli_free_result($result);
                  ?>
                </select>
              </div>
            </div>
          </div>
            
          <!-- Password Info -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Password</label>
                <input type="password" name="createUserPassword" class="form-control" required autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Confirm Password</label>
                <input type="password" name="repeatUserPassword" class="form-control" required autocomplete="off">
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="reset" class="btn btn-danger">Reset</button>
          <button type="submit" class="btn btn-success">Create User</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Create User Modal -->

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="card-title">Update User</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body md-form" id="updateUser-form">
        <div class="modal-body">

          <input type="hidden" name="action">
          <input type="hidden" name="updateUserIdentity">

          <!-- Role Info -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="role" class="bmd-label-floating">Role</label>
                <select class="form-control" name="updateUserRole" required>
                  <option value="admin">Admin</option>
                  <option value="caretaker">Department Head</option>
                  <option value="user">User</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Department Info -->
          <div class="row d-none">
            <div class="col-md-12">
              <div class="form-group">
                <label for="department" class="bmd-label-floating">Department</label>
                <select class="form-control" name="updateUserDepartment" required>
                  <?php
                    $sql = "SELECT * FROM departments";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0)
                    {
                      $count = 1;
                      while($row = mysqli_fetch_assoc($result))
                      {
                        if($count == 1)
                        {
                          echo "<option value=".$row['code'].">".$row['name']."</option>";
                          $count += 1;
                        }
                        else
                          echo "<option value=".$row['code'].">".$row['name']."</option>";                        
                      }
                    }
                    else
                    {
                      echo "<option value='null' selected>No Department Found!</option>";
                    }
                    // Free result set
                    mysqli_free_result($result);
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Update User Modal -->

<script>
  // Toggle Department Select Menu
  $(document).ready(function(){
    
    // Start Create User Form
    // Toggle Department Dropdown with respect to Role
    $("#createUser-form select[name='createUserRole']").change(function(){
      $('#createUser-form select[name="createUserDepartment"]').parents('div.row').addClass('d-none');
      var newRole = $(this).children("option:selected").val();
      if( newRole != 'user' )
        $('#createUser-form select[name="createUserDepartment"]').parents('div.row').removeClass('d-none');
    });
    // Hide Department Selection on Reset
    $("#createUser-form button[type='reset']").on("click", function () {
      $('#createUser-form select[name="createUserDepartment"]').parents('div.row').addClass('d-none');
    });

    // Create User form Request
    $('#createUser-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#createUser-form input, #createUser-form select').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/createUser.inc.php',
        data : $('#createUser-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#createUser-form button[type="submit"]').html('Creating User...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#createUser-form button[type='reset']").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                if( key == 'createUserDepartment' )
                {
                  $("#createUser-form select[name="+key+"]").addClass('is-invalid')
                  .after('<div class="invalid-feedback">'+value+'</div>');
                }
                else
                {
                  $("#createUser-form input[name="+key+"]").addClass('is-invalid')
                  .after('<div class="invalid-feedback">'+value+'</div>');
                }
              }
            });
            $("#createUser-form input[name='createPassword'], #createUser-form input[name='repeatPassword']").val('');
          }
          else
          {
            // alert('success');
            $("#createUser-form")[0].reset();
            $("#createUser-form .modal-footer").before('<div class="alert alert-success">User created successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#createUser-form button[type="submit"]').html('Create User').removeAttr('disabled');
        },
        error: function(){$("#createUser-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
      });
    });
    // End Create User Form

    // Start Update User Form
    // Called before the below modal is opened - Bootstrap event
    $('#updateUserModal').on('show.bs.modal', function (e) {
      $('#updateUser-form')[0].reset();
      $('#updateUser-form select[name="updateUserDepartment"]').parents('div.row').addClass('d-none');
      
      row = $(e.relatedTarget).parent().siblings().map(function() {return $(this).text();}).get();
      
      $('#updateUser-form input[name="action"]').val('update');
      $('#updateUser-form input[name="updateUserIdentity"]').val(row[0]);
      $('#updateUser-form select[name="updateUserRole"] option[value="'+row[5]+'"]').prop('selected', true);
      $('#updateUser-form select[name="updateUserDepartment"] option[value="'+row[6]+'"]').prop('selected', true);
      if( row[5] != 'user' )
        $('#updateUser-form select[name="updateUserDepartment"]').parents('div.row').removeClass('d-none');
    });
    
    // Toggle Department Dropdown with respect to Role
    $("#updateUser-form select[name='updateUserRole']").change(function(){
      $('#updateUser-form select[name="updateUserDepartment"]').parents('div.row').addClass('d-none');
      var newRole = $(this).children("option:selected").val();
      if(newRole != 'user')
        $('#updateUser-form select[name="updateUserDepartment"]').parents('div.row').removeClass('d-none');
    });
    $('#updateUser-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#updateUser-form select').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/updateUser.inc.php',
        data : $('#updateUser-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#updateUser-form button[type="submit"]').html('Updating ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#updateUser-form .modal-footer").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                $("#updateUser-form select[name="+key+"]").addClass('is-invalid')
                .after('<div class="invalid-feedback">'+value+'</div>');
              }
            });
          }
          else
          {
            // alert('success');
            $("#updateUser-form")[0].reset();
            $("#updateUser-form .modal-footer").before('<div class="alert alert-success">User Updated successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#updateUser-form button[type="submit"]').html('Update User').removeAttr('disabled');
        },
        error: function(){$("#updateUser-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
      });
    });
    // End Update User Form

    // Delete Action Button Request
    $('#user-table button#deleteUser').click(function(e){
      e.preventDefault();

      action = $(this).attr('id').split('U')[0];
      name   = $(this).parent().siblings('.d-none').text();

      //create an ajax request for the specified action
      $.ajax({
        type: "POST",
        url: "./includes/updateUser.inc.php",
        data: {action: action, name: name},
        dataType : 'json',
        success : function(data){
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                md.showNotification('top', 'right', 'warning', value);
              }
            });
          }
          else
          {
            // alert('success');
            md.showNotification('top', 'right', 'warning', 'User deleted successfully');
            setTimeout(function(){location.reload();},4000);
          }
        },
        error: function(data){md.showNotification('top', 'right', 'danger', 'Something went Wrong! Try Again');}
      });
    });
  });
</script>
<?php
  }
  else
  {
    header('Location: ./');
    exit();
  }
?>