<?php
header('Content-type: application/json');
session_start();

$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    require_once('../../includes/functions.inc.php');
    
    $subject     = $_POST['complaintSubject'];
    $description = $_POST['complaintBody'];
    $department  = $_POST['complaintDepartment'];
    
    // Initial Validation and Error Handling
    if( strlen($subject) < 8 || strlen($subject) > 100 )
    {
        $error_log['complaintSubject'] = 'Invalid Subject!';
    }
    if( strlen($description) < 10 || strlen($description) > 255 )
    {
        $error_log['complaintBody'] = 'Must be 10 to 255 characters!';
    }
    // Initial Validation End

    // Check if there's any Error otherwise continue with signup
    if(count($error_log) == 0)
    {
        require_once('../../config/db.php');
        
        // if not then insert user details to the table
        $sql = "INSERT INTO complaints (subject, description, dept_id, user_id, created_at) VALUES (?,?,?,?,NOW())";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $error_log['sql'] = "Connection Failed! Please try again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "ssss", $subject, $description, $department, $_SESSION['userId']);
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