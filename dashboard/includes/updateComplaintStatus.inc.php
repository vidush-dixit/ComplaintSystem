<?php
header('Content-type: application/json');
session_start();

$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    $status = $_POST['action'];
    $rowId  = $_POST['name'];
    
    
    require_once('../../config/db.php');
    
    // if not then insert user details to the table
    $sql = "UPDATE `complaints` SET `status`=? WHERE `id`=?";
    
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql))
    {
        $response['status'] = 'error';
        $error_log['sql'] = "Connection Failed! Please try again";
    }
    else
    {
        mysqli_stmt_bind_param($stmt, "ss", $status, $rowId);
        mysqli_stmt_execute($stmt);

        $response['status'] = 'success';
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    $response['errors'] = $error_log;
    echo json_encode($response);
}
else
{
    header('Location: ../');
    exit();
}

?>