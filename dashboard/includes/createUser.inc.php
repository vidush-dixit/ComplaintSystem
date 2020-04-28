<?php
header('Content-type: application/json');

$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    require_once('../../includes/functions.inc.php');
    
    $name           = $_POST['createUserName'];
    $email          = $_POST['createUserEmail'];
    $phone          = $_POST['createUserPhone'];
    $role           = $_POST['createUserRole'];
    
    if( $role != 'user' )
        $department = $_POST['createUserDepartment'];
    else
        $department = null;
    
    $password       = $_POST['createUserPassword'];
    $repeatPassword = $_POST['repeatUserPassword'];

    // Initial Validation and Error Handling
    if( !validationName($name) )
    {
        $error_log['createUserName'] = 'Invalid Name!';
    }
    if( !validationEmail($email) )
    {
        $error_log['createUserEmail'] = 'Invalid Email!';
    }
    if( !validationPhone($phone) )
    {
        $error_log['createUserPhone'] = 'Invalid Phone!';
    }
    if( !validationPassword($password) )
    {
        $error_log['createUserPassword'] = 'Must be min. 8 characters!';
    }
    if( !validationRepeatPassword($password, $repeatPassword) )
    {
        $error_log['repeatUserPassword'] = 'Must match Password!';
    }
    if( $role != 'user' && ( $department == null || empty($department) || $department == 'null' ) )
    {
        $error_log['createUserDepartment'] = 'Select a Department';
    }
    // Initial Validation End

    // Check if there's any Error otherwise continue with signup
    if( count($error_log) == 0 )
    {
        require_once('../../config/db.php');
        
        // Check if entered email is unique or not
        $sql = "SELECT email FROM users WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        if( !mysqli_stmt_prepare($stmt, $sql) )
        {
            $error_log['sql'] = "Connection Failed! Please try again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if( mysqli_stmt_num_rows($stmt) > 0 )
            {
                $error_log['createUserEmail'] = "Email already Registered!";
                $response['status'] = 'error';
            }
            else
            {
                // if not then insert user details to the table
                $sql = "INSERT INTO users (name, phone, email, role, dept_id, password, created_at) VALUES (?,?,?,?,?,?,NOW())";

                $stmt = mysqli_stmt_init($conn);
                if( !mysqli_stmt_prepare($stmt, $sql) )
                {
                    $error_log['sql'] = "Connection Failed! Please try again";
                    $response['status'] = 'error';
                }
                else
                {
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                    
                    mysqli_stmt_bind_param($stmt, "ssssss", $name, $phone, $email, $role, $department, $hashedPwd);
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

// name, phone, email, email_verified_at, role, dept_id, password, profile_picture, remember_token, created_at, updated_at
?>