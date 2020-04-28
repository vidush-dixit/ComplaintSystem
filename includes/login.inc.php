<?php

header('Content-type: application/json');

$response = array();
$error_log = array();

if($_POST)
{
    require_once('./functions.inc.php');
    
    $email    = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];
    
    // Initial Validation and Error Handling
    if(!validationEmail($email))
    {
        $error_log['loginEmail'] = 'Invalid Email!';
    }
    if(empty($password))
    {
        $error_log['loginPassword'] = 'Must be min. 8 characters!';
    }
    // Initial Validation End

    // Check if there's no Empty fields continue with signup
    if(count($error_log) == 0)
    {
        require_once('../config/db.php');

        // Fetch data from users table to match password
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $error_log['sql'] = "Oops! Something went Wrong. Try Again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result))
            {
                // Match User Entered Password with Database Password
                $pwdCheck = password_verify($password, $row['password']);
                if(!$pwdCheck)
                {
                    // Wrong Password. So send back to index page login modal
                    $error_log['loginPassword'] = "Incorrect Password!";
                    $response['status'] = 'error';
                }
                else if($pwdCheck)
                {
                    // Login Successful. Return to index page
                    $response['status'] = 'success';
                    // Start Session to login user
                    session_start();
                    $_SESSION['userId']   = $row['id'];
                    $_SESSION['userType'] = $row['role'];
                    $parts = explode(' ',$row['name']);
                    $_SESSION['userName'] = $parts[0];
                }
                else
                {
                    // Just in case if pwdCheck is not boolean. So return to index page login modal stating Wrong Password
                    $error_log['loginPassword'] = "Incorrect Password!";
                    $response['status'] = 'error';
                }
            }
            else
            {
                // User with entered Email Doesn't Exist
                $error_log['loginEmail'] = "User not Found!";
                $response['status'] = 'error';
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
    // If page is visited without clicking on login button
    header('Location: ../');
    exit();
}
?>
