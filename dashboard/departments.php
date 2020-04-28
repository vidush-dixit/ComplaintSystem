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
            <div class="col-12 col-xl-9 col-lg-8">
              <h4 class="card-title">Departments</h4>
              <p class="card-category">Listing of all available Departments</p>
            </div>
            <div class="col-12 col-xl-3 col-lg-3">
                <button class="card-title btn btn-success pl-0 mt-1" data-toggle="modal" data-target="#createDepartmentModal"><i class="material-icons pl-3 pr-2">add</i>Add Department</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="departments-table">
              <thead class="text-primary text-center">
                <th>ID</th><th>Department Code</th><th>Department Name</th><th>Action</th>
              </thead>
              <tbody>
                <?php
                  $sql = "SELECT * FROM departments";
                  $result = mysqli_query($conn, $sql);
                  $id = 0;

                  if (mysqli_num_rows($result) > 0)
                  {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      $id += 1;
                      echo "<tr class=\"text-center\">";
                      echo "<td class=\"d-none\">".$row['id']."</td>";
                      echo "<td>".$id."</td><td>".$row['code']."</td><td>".$row['name']."</td>";

                      echo "<td><button class=\"btn btn-info btn-round btn-fab\" id=\"updateDepartment\" data-toggle=\"modal\" data-target=\"#updateDepartmentModal\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Edit\">create</i></button><button class=\"btn btn-danger btn-round btn-fab\" id=\"deleteDepartment\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Remove\">delete_forever</i></button></td>";
                      echo "</tr>";
                    }
                  }
                  else
                  {
                    echo "<tr class=\"text-center\">";
                    echo "<td>-</td><td>-</td><td>-</td>";
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

<!-- Create Departments Modal -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="modal-title card-title" id="exampleModalLongTitle">Add New Department</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body" id="createDepartment-form">
        <div class="modal-body">
          
          <!-- Department Code -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Department Code</label>
                <input type="text" name="departmentCode" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Department Name -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Department Name</label>
                <input type="text" name="departmentName" class="form-control" required>
              </div>
            </div>
          </div>
          
          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="reset" class="btn btn-danger btn-round">Reset</button>
          <button type="submit" class="btn btn-success btn-round">Add Department</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Create Departments Modal -->

<!-- Update Departments Modal -->
<div class="modal fade" id="updateDepartmentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="modal-title card-title" id="exampleModalLongTitle">Update Department</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body" id="updateDepartment-form">
        <div class="modal-body">
          
          <input type="hidden" name="action">
          <input type="hidden" name="departmentNewIdentity">

          <!-- Department Code -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Department Code</label>
                <input type="text" name="departmentNewCode" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Department Name -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Department Name</label>
                <input type="text" name="departmentNewName" class="form-control" required>
              </div>
            </div>
          </div>
          
          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-info btn-round">Update Department</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Update Departments Modal -->

<script>
  
  $(document).ready(function(){

    // Update Action Button Request
    // Called before the below modal is opened - Bootstrap event
    $('#updateDepartmentModal').on('show.bs.modal', function (e) {
      row = $(e.relatedTarget).parent().siblings().map(function() {return $(this).text();}).get();
      
      $('#updateDepartment-form input[name="action"]').val('update');
      $('#updateDepartment-form input[name="departmentNewIdentity"]').val(row[0]);
      $('#updateDepartment-form input[name="departmentNewCode"]').val(row[2]);
      $('#updateDepartment-form input[name="departmentNewName"]').val(row[3]);
    });

    // Delete Action Button Request
    $('#departments-table button#deleteDepartment').click(function(e){
      e.preventDefault();

      action = $(this).attr('id').split('D')[0];
      name   = $(this).parent().siblings('.d-none').text();

      //create an ajax request for the specified action
      $.ajax({
        type: "POST",
        url: "./includes/updateDepartment.inc.php",
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
            md.showNotification('top', 'right', 'warning', 'Department deleted successfully');
            setTimeout(function(){location.reload();},4000);
          }
        },
        error: function(data){md.showNotification('top', 'right', 'danger', 'Something went Wrong! Try Again');}
      });
    });

    // Create Department form Request
    $('#createDepartment-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#createDepartment-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/createDepartment.inc.php',
        data : $('#createDepartment-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#createDepartment-form button[type="submit"]').html('Adding Department...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#createDepartment-form button[type='reset']").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                $("#createDepartment-form input[name="+key+"]").addClass('is-invalid')
                .after('<div class="invalid-feedback">'+value+'</div>');
              }
            });
          }
          else
          {
            // alert('success');
            $("#createDepartment-form")[0].reset();
            $("#createDepartment-form .modal-footer").before('<div class="alert alert-success">Department created successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#createDepartment-form button[type="submit"]').html('Add Department').removeAttr('disabled');
        },
        error: function(){$("#createDepartment-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
      });
    });

    // Update Department form Request
    $('#updateDepartment-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#updateDepartment-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/updateDepartment.inc.php',
        data : $('#updateDepartment-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#updateDepartment-form button[type="submit"]').html('Updating ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#updateDepartment-form .modal-footer").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                $("#updateDepartment-form input[name="+key+"]").addClass('is-invalid')
                .after('<div class="invalid-feedback">'+value+'</div>');
              }
            });
          }
          else
          {
            // alert('success');
            $("#updateDepartment-form")[0].reset();
            $("#updateDepartment-form .modal-footer").before('<div class="alert alert-success">Department updated successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#updateDepartment-form button[type="submit"]').html('Update Department').removeAttr('disabled');
        },
        error: function(){$("#updateDepartment-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
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