<?php
  session_start();
  include_once('../config/db.php');
?>
<div class="container-fluid">
  <?php
    if( $_SESSION['userType'] != 'user' )
    {
  ?>
  <!-- Start Table for Complaints To You/Your Department -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
          <div class="row">
            <div class="col-12 col-xl-9 col-lg-8">
              <h4 class="card-title">Complaints To You</h4>
              <p class="card-category">Listing of all Complaints with Status and Actions filed to your Department</p>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="complaints-table">
              <thead class="text-primary text-center">
                <th>ID</th><th>Subject</th><th>Description</th><th>Date</th><th>Status</th><?php if ( $_SESSION['userType'] != 'user' ) {echo"<th>Action</th>";}?>
              </thead>
              <tbody>
              <?php
                  $sql = "SELECT complaints.id,complaints.subject,complaints.description,complaints.created_at,complaints.status FROM `complaints` JOIN `users` ON complaints.dept_id=users.dept_id WHERE users.id='".$_SESSION['userId']."'";
                  $result = mysqli_query($conn, $sql);
                  $id = 0;

                  if (mysqli_num_rows($result) > 0)
                  {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      $id += 1;
                      echo "<tr class=\"text-center\">";
                      echo "<td class=\"d-none\">".$row['id']."</td>";
                      echo "<td>".$id."</td><td>".$row['subject']."</td><td>".$row['description']."</td><td>".$row['created_at']."</td><td class=\"text-primary font-weight-bold\">".$row['status']."</td>";

                      if ( $_SESSION['userType'] != 'user' )
                      {
                        if ( $row['status'] == 'pending' )
                          echo "<td><button class=\"btn btn-info btn-round btn-fab\" id=\"approved\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Approve\">thumb_up_alt</i></button><button class=\"btn btn-danger btn-round btn-fab\" id=\"rejected\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Reject\">thumb_down_alt</i></button></td>";
                        else if ( $row['status'] == 'approved')
                          echo "<td><button class=\"btn btn-success btn-round btn-fab\" id=\"fixed\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Fixed\">build</i></button></td>";
                        else if ( $row['status'] == 'rejected' )
                          echo "<td>No Action</td>";
                        else
                          echo "<td>No Action</td>";
                      }
                      echo "</tr>";
                    }
                  }
                  else
                  {
                    echo "<tr class=\"text-center\">";
                    echo "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";
                    if( $_SESSION['userType'] != 'user' ) echo "<td>-</td>";
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
  <!-- End Table for Complaints To You/Your Department -->
  <?php
    }
    if ( $_SESSION['userType'] != 'admin' )
    {
  ?>
  <!-- Start Table for Complaints By You -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
          <div class="row">
            <div class="col-12 col-xl-9 col-lg-8">
              <h4 class="card-title">Complaints By You</h4>
              <p class="card-category">Listing of all Complaints with Status filed by You</p>
            </div>
            <?php
              if ( $_SESSION['userType'] != 'admin' )
              {
            ?>
            <div class="col-12 col-xl-3 col-lg-3">
                <button class="card-title btn btn-success pl-0 mt-1" data-toggle="modal" data-target="#createComplaintModal"><i class="material-icons pl-3 pr-2">add</i>New Complaint</button>
            </div>
            <?php
              }
            ?>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="complaints-table">
              <thead class="text-primary text-center">
                <th>ID</th><th>Department</th><th>Subject</th><th>Description</th><th>Date</th><th>Status</th>
              </thead>
              <tbody>
              <?php
                  $sql = "SELECT * FROM complaints where user_id='".$_SESSION['userId']."'";
                  $result = mysqli_query($conn, $sql);
                  $id = 0;

                  if (mysqli_num_rows($result) > 0)
                  {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      $id += 1;
                      echo "<tr class=\"text-center\">";
                      echo "<td class=\"d-none\">".$row['id']."</td>";
                      echo "<td>".$id."</td><td>".$row['dept_id']."</td><td>".$row['subject']."</td><td>".$row['description']."</td><td>".$row['created_at']."</td><td class=\"text-primary font-weight-bold\">".$row['status']."</td>";
                      echo "</tr>";
                    }
                  }
                  else
                  {
                    echo "<tr class=\"text-center\">";
                    echo "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";
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
  <!-- End Table for Complaints By You -->
  <?php
    }
  ?>
</div>

<!-- Create Complaints Modal -->
<!-- Modal -->
<div class="modal fade" id="createComplaintModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="modal-title card-title" id="exampleModalLongTitle">Register New Complaint</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="createComplaint-form" method="post" class="card-body">
        <div class="modal-body">
          
          <!-- Category -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="Role" class="bmd-label-floating">Department</label>
                <select class="form-control" name="complaintDepartment" required>
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
                          echo "<option value=".$row['code']." selected>".$row['name']."</option>";
                          $count += 1;
                        }
                        else
                          echo "<option value=".$row['code'].">".$row['name']."</option>";                        
                      }
                    }
                    else
                    {
                      echo "<option value='NULL' selected>No Department Found!</option>";
                    }
                    // Free result set
                    mysqli_free_result($result);
                  ?>
                </select>
              </div>
            </div>
          </div>
          
          <!-- Subject -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Subject</label>
                <input type="text" name="complaintSubject" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="complaintBody" aria-describedby="descriptionHelp" rows="3" required></textarea>
                <small id="descriptionHelp" class="form-text text-muted">Max. word limit - 250</small>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="reset" class="btn btn-danger btn-round">Reset</button>
          <button type="submit" class="btn btn-success btn-round">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Toggle Department Select Menu
  $(document).ready(function(){
    
    // Create Complaint form Request
    $('#createComplaint-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#createComplaint-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/createComplaint.inc.php',
        data : $('#createComplaint-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#createComplaint-form button[type="submit"]').html('Submitting ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#createComplaint-form button[type='reset']").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                if( key == 'complaintBody' )
                {
                    $("#createComplaint-form textarea[name="+key+"]").addClass('is-invalid')
                    .after('<div class="invalid-feedback">'+value+'</div>');
                }
                else
                {
                    $("#createComplaint-form input[name="+key+"]").addClass('is-invalid')
                    .after('<div class="invalid-feedback">'+value+'</div>');
                }
              }
            });
          }
          else
          {
            // alert('success');
            $("#createComplaint-form")[0].reset();
            $("#createComplaint-form .modal-footer").before('<div class="alert alert-success">Complaint submitted successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#createComplaint-form button[type="submit"]').html('Create').removeAttr('disabled');
        },
        error: function(){$("#createComplaint-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
      });
    });

    // Action Buttons Request
    $('#complaints-table button').click(function(e){
      e.preventDefault();

      action = $(this).attr('id');
      name   = $(this).parent().siblings('.d-none').text();
      
      //create an ajax request for the specified action
      $.ajax({
        type: "POST",
        url: "./includes/updateComplaintStatus.inc.php",
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
            switch(action)
            {
              case 'approved': setAlertColor = 'info'; break;
              case 'rejected': setAlertColor = 'warning'; break;
              case 'fixed': setAlertColor = 'success'; break;
            }

            md.showNotification('top', 'right', setAlertColor, 'Complaint status updated to '+action);
            setTimeout(function(){location.reload();},4000);
          }
        },
        error: function(){md.showNotification('top', 'right', 'danger', 'Something went Wrong! Try Again');}
      });
    });
  });
</script>