<?php
header('Content-type: application/json');
session_start();

$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    require_once('../../includes/functions.inc.php');
    
    $title  = $_POST['announcementTitle'];
    $body   = $_POST['announcementBody'];
    
    // Initial Validation and Error Handling
    if( empty($title) || strlen($title) > 100 )
    {
        $error_log['announcementTitle'] = 'Invalid Title!';
    }
    if( strlen($body) < 10 || strlen($body) > 255 )
    {
        $error_log['announcementBody'] = 'Must be 10 to 255 characters!';
    }
    // Initial Validation End

    // Check if there's any Error otherwise continue with signup
    if(count($error_log) == 0)
    {
        require_once('../../config/db.php');
        
        // if not then insert user details to the table
        $sql = "INSERT INTO announcements (title, body, owner_id, created_at) VALUES (?,?,?,NOW())";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $error_log['sql'] = "Connection Failed! Please try again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "sss", $title, $body, $_SESSION['userId']);
            mysqli_stmt_execute($stmt);

            $response['status'] = 'success';
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    else
    {
        // If errors are there return to index page with errors
        $response['status'] = 'error';
    }
    $response['errors'] = $error_log;
    echo json_encode($response);
}
else
{
    header('Location: ../');
    exit();
}

?>