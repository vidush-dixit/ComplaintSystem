<?php
header('Content-type: application/json');

$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    require_once('./functions.inc.php');
    
    $name           = $_POST['signupName'];
    $email          = $_POST['signupEmail'];
    $phone          = $_POST['signupPhone'];
    $password       = $_POST['signupPassword'];
    $repeatPassword = $_POST['repeatPassword'];
    if(isset($_POST['agreeTerms']))
        $agreeTerms = $_POST['agreeTerms'];
    else
        $agreeTerms = '';

    // Initial Validation and Error Handling
    if(!validationName($name))
    {
        $error_log['signupName'] = 'Invalid Name!';
    }
    if(!validationEmail($email))
    {
        $error_log['signupEmail'] = 'Invalid Email!';
    }
    if(!validationPhone($phone))
    {
        $error_log['signupPhone'] = 'Invalid Phone!';
    }
    if(!validationPassword($password))
    {
        $error_log['signupPassword'] = 'Must be min. 8 characters!';
    }
    if(!validationRepeatPassword($password, $repeatPassword))
    {
        $error_log['repeatPassword'] = 'Must match Password!';
    }
    if($agreeTerms != 'agree')
    {
        $error_log['agreeTerms'] = 'You must agree before registering';
    }
    // Initial Validation End

    // Check if there's any Error otherwise continue with signup
    if(count($error_log) == 0)
    {
        require_once('../config/db.php');
        
        // Check if entered email is unique or not
        $sql = "SELECT email FROM users WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $error_log['sql'] = "Connection Failed! Please try again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0)
            {
                $error_log['signupEmail'] = "Email already Registered!";
                $response['status'] = 'error';
            }
            else
            {
                // if not then register insert user details to the table
                $sql = "INSERT INTO users (name, phone, email, role, password, created_at) VALUES (?,?,?,?,?,NOW())";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql))
                {
                    $error_log['sql'] = "Connection Failed! Please try again";
                    $response['status'] = 'error';
                }
                else
                {
                    $role = 'user';
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                    
                    mysqli_stmt_bind_param($stmt, "sssss", $name, $phone, $email, $role, $hashedPwd);
                    mysqli_stmt_execute($stmt);
                    $response['status'] = 'success';
                    
                    // Start Session
                    session_start();
                    $_SESSION['userId']   = mysqli_stmt_insert_id($stmt);
                    $_SESSION['userType'] = $role;
                    $parts = explode(' ',$name);
                    $_SESSION['userName'] = $parts[0];
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