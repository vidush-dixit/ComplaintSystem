<?php
header('Content-type: application/json');

$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    require_once('../../includes/functions.inc.php');
    
    $name = $_POST['departmentName'];
    $code = strtoupper($_POST['departmentCode']);
    
    // Initial Validation and Error Handling
    if(!validationName($name))
    {
        $error_log['departmentName'] = 'Invalid Name!';
    }
    if(empty($code) || strlen($code) > 6)
    {
        $error_log['departmentCode'] = 'Must be 3 to 6 characters in length!';
    }
    // Initial Validation End

    // Check if there's any Error otherwise continue with signup
    if(count($error_log) == 0)
    {
        require_once('../../config/db.php');
        
        // Check if entered email is unique or not
        $sql = "SELECT code FROM departments WHERE code=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $error_log['sql'] = "Connection Failed! Please try again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "s", $code);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) > 0)
            {
                $error_log['departmentName'] = "Department already Exists!";
                $error_log['departmentCode'] = "Department already Exists!";
                $response['status'] = 'error';
            }
            else
            {
                // if not then insert user details to the table
                $sql = "INSERT INTO departments (name, code, created_at) VALUES (?,?,NOW())";

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql))
                {
                    $error_log['sql'] = "Connection Failed! Please try again";
                    $response['status'] = 'error';
                }
                else
                {
                    mysqli_stmt_bind_param($stmt, "ss", $name, $code);
                    mysqli_stmt_execute($stmt);

                    $response['status'] = 'success';
                }
            }
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