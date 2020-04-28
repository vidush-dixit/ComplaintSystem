<?php
header('Content-type: application/json');
session_start();
    
$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    $status = $_POST['action'];
    
    if ( $status == 'update' )
    {
        $rowId     = $_POST['announcementNewIdentity'];
        $newTitle  = $_POST['announcementNewTitle'];
        $newBody   = $_POST['announcementNewBody'];

        // Initial Validation and Error Handling
        if( empty($newTitle) || strlen($newTitle) > 100 )
        {
            $error_log['announcementTitle'] = 'Invalid Title!';
        }
        if( strlen($newBody) < 10 || strlen($newBody) > 255 )
        {
            $error_log['announcementBody'] = 'Must be 10 to 255 characters!';
        }
        // Initial Validation End

        if(count($error_log) == 0)
        {
            require_once('../../config/db.php');
            // if not then insert user details to the table
            $sql = "UPDATE `announcements` SET `title`=?,`body`=?,`created_at`=NOW() WHERE `id`=?";
            
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                $response['status'] = 'error';
                $error_log['sql'] = "Connection Failed! Please try again";
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "sss", $newTitle, $newBody, $rowId);
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
    }
    else if( $status == 'delete' )
    {
        $rowId  = $_POST['name'];
        
        require_once('../../config/db.php');

        $sql = "DELETE FROM `announcements` WHERE `id`=?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $response['status'] = 'error';
            $error_log['sql'] = "Connection Failed! Please try again";
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "s", $rowId);
            mysqli_stmt_execute($stmt);

            $response['status'] = 'success';
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    else
    {
        // Protection from invalid input
        $response['status'] = 'error';
        $error_log['sql'] = "Something went Wrong! Try Again";
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