<?php
  session_start();
  include_once('../config/db.php');
  if ( $_SESSION['userType'] != 'user' )
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
              <h4 class="card-title">Announcements</h4>
              <p class="card-category">Listing of all Announcements</p>
            </div>
            <div class="col-12 col-xl-3 col-lg-3">
                <button class="card-title btn btn-success pl-0 mt-1" data-toggle="modal" data-target="#createAnnouncementModal"><i class="material-icons pl-3 pr-2">add</i>New Announcement</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="announcements-table">
              <thead class="text-primary text-center">
                <th>ID</th><th>Subject</th><th>Message</th><th>Date</th><th>Action</th>
              </thead>
              <tbody>
                <?php
                  $sql = "SELECT * FROM announcements WHERE owner_id='".$_SESSION['userId']."'";
                  $result = mysqli_query($conn, $sql);
                  $id = 0;

                  if (mysqli_num_rows($result) > 0)
                  {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      $id += 1;
                      echo "<tr class=\"text-center\">";
                      echo "<td class=\"d-none\">".$row['id']."</td>";
                      echo "<td>".$id."</td><td>".$row['title']."</td><td>".$row['body']."</td><td>".$row['created_at']."</td>";

                      echo "<td><button class=\"btn btn-info btn-round btn-fab\" id=\"updateAnnouncement\" data-toggle=\"modal\" data-target=\"#updateAnnouncementModal\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Edit\">create</i></button><button class=\"btn btn-danger btn-round btn-fab\" id=\"deleteAnnouncement\"><i class=\"material-icons\" data-toggle=\"tooltip\" data-html=\"true\" title=\"Remove\">delete_forever</i></button></td>";
                      echo "</tr>";
                    }
                  }
                  else
                  {
                    echo "<tr class=\"text-center\">";
                    echo "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";
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

<!-- Create Announcements Modal -->
<div class="modal fade" id="createAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="modal-title card-title" id="exampleModalLongTitle">Create New Announcement</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body" id="createAnnouncement-form">
        <div class="modal-body">
          
          <!-- Title -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Title / Subject</label>
                <input type="text" name="announcementTitle" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="content">Message</label>
                <textarea class="form-control" name="announcementBody" aria-describedby="contentHelp" rows="3" required></textarea>
                <small id="contentHelp" class="form-text text-muted">Max. word limit - 250</small>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="reset" class="btn btn-danger btn-round">Reset</button>
          <button type="submit" class="btn btn-success btn-round">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Create Announcement Modal -->

<!-- Update Announcements Modal -->
<div class="modal fade" id="updateAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header-primary">
        <h5 class="modal-title card-title" id="exampleModalLongTitle">Update Announcement</h5>
        <button type="button" class="close card-header-icon" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="card-body" id="updateAnnouncement-form">
        <div class="modal-body">
          
          <input type="hidden" name="action">
          <input type="hidden" name="announcementNewIdentity">

          <!-- Title -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Title / Subject</label>
                <input type="text" name="announcementNewTitle" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="content">Message</label>
                <textarea class="form-control" name="announcementNewBody" aria-describedby="contentHelp" rows="3" required></textarea>
                <small id="contentHelp" class="form-text text-muted">Max. word limit - 250</small>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-info btn-round">Update Announcement</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Update Announcement Modal -->

<script>
  
  $(document).ready(function(){
    
    // Update Action Button Request
    // Called before the below modal is opened - Bootstrap event
    $('#updateAnnouncementModal').on('show.bs.modal', function (e) {
      row = $(e.relatedTarget).parent().siblings().map(function() {return $(this).text();}).get();
      
      $('#updateAnnouncement-form input[name="action"]').val('update');
      $('#updateAnnouncement-form input[name="announcementNewIdentity"]').val(row[0]);
      $('#updateAnnouncement-form input[name="announcementNewTitle"]').val(row[2]);
      $('#updateAnnouncement-form textarea[name="announcementNewBody"]').val(row[3]);
    });

    // Delete Action Button Request
    $('#announcements-table button#deleteAnnouncement').click(function(e){
      e.preventDefault();

      action = $(this).attr('id').split('A')[0];
      name   = $(this).parent().siblings('.d-none').text();
      
      //create an ajax request for the specified action
      $.ajax({
        type: "POST",
        url: "./includes/updateAnnouncement.inc.php",
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
            md.showNotification('top', 'right', 'warning', 'Announcement deleted successfully');
            setTimeout(function(){location.reload();},4000);
          }
        },
        error: function(data){md.showNotification('top', 'right', 'danger', 'Something went Wrong! Try Again');}
      });
    });
    
    // Create Announcement form Request
    $('#createAnnouncement-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#createAnnouncement-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/createAnnouncement.inc.php',
        data : $('#createAnnouncement-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#createAnnouncement-form button[type="submit"]').html('Creating ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#createAnnouncement-form button[type='reset']").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                if( key == 'announcementBody' )
                {
                    $("#createAnnouncement-form textarea[name="+key+"]").addClass('is-invalid')
                    .after('<div class="invalid-feedback">'+value+'</div>');
                }
                else
                {
                    $("#createAnnouncement-form input[name="+key+"]").addClass('is-invalid')
                    .after('<div class="invalid-feedback">'+value+'</div>');
                }
              }
            });
          }
          else
          {
            // alert('success');
            $("#createAnnouncement-form")[0].reset();
            $("#createAnnouncement-form .modal-footer").before('<div class="alert alert-success">Announcement created successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#createAnnouncement-form button[type="submit"]').html('Create').removeAttr('disabled');
        },
        error: function(){$("#createAnnouncement-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
      });
    });

    // Update Announcement form Request
    $('#updateAnnouncement-form button[type="submit"]').click(function(e){
      e.preventDefault();
      
      $('#updateAnnouncement-form input').removeClass('is-invalid');
      $('.invalid-feedback').remove();
      
      $.ajax({
        type : 'POST',
        url  : './includes/updateAnnouncement.inc.php',
        data : $('#updateAnnouncement-form').serialize(),
        dataType : 'json',
        success : function(data){
          $('#updateAnnouncement-form button[type="submit"]').html('Updating ...').attr('disabled', 'disabled');
          if ( data.status === 'error' )
          {
            // alert('error');
            $.each( data.errors, function(key, value){
              if( key == 'sql' )
              {
                $("#updateAnnouncement-form .modal-footer").before('<div class="alert alert-danger">'+value+'</div>');
              }
              else
              {
                if( key == 'announcementNewBody' )
                {
                    $("#updateAnnouncement-form textarea[name="+key+"]").addClass('is-invalid')
                    .after('<div class="invalid-feedback">'+value+'</div>');
                }
                else
                {
                    $("#updateAnnouncement-form input[name="+key+"]").addClass('is-invalid')
                    .after('<div class="invalid-feedback">'+value+'</div>');
                }
              }
            });
          }
          else
          {
            // alert('success');
            $("#updateAnnouncement-form")[0].reset();
            $("#updateAnnouncement-form .modal-footer").before('<div class="alert alert-success">Announcement updated successfully.</div>');
            setTimeout(function(){location.reload();},1500);
          }
          $('#updateAnnouncement-form button[type="submit"]').html('Update Announcement').removeAttr('disabled');
        },
        error: function(){$("#updateAnnouncement-form .modal-footer").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
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