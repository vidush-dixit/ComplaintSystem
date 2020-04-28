<?php

header('Content-type: application/json');

$response = array();
$error_log = array();

if($_POST)
{
    require_once('./functions.inc.php');

    $selector = $_POST['resetSelector'];
    $validator = $_POST['resetValidator'];
    $password = $_POST['resetNewPass'];
    $repeatPassword = $_POST['resetRepeatPass'];

    // Initial Validation
    if(!validationPassword($password))
    {
        $error_log['resetNewPass'] = 'Must be min. 8 characters!';
    }
    if(!validationRepeatPassword($password, $repeatPassword))
    {
        $error_log['resetRepeatPass'] = 'Must match Password!';
    }
    // Initial Validation End

    // Check if there's any Initial Error otherwise continue with reset password
    if(count($error_log) == 0)
    {
        $currentDate = date("U");

        require_once('../config/db.php');
        
        // Check for valid selector entry and expiry time
        $sql = "SELECT * FROM password_resets WHERE selector = ? AND expires_at >= ?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            $error_log['sql'] = "Connection Failed! Please try again";
            $response['status'] = 'error';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            
            if(!$row = mysqli_fetch_assoc($result))
            {
                // if request time expired or no request exist in DB
                $error_log['sql'] = "Invalid Request!<br>Please re-submit reset request using your Email";
                $response['status'] = 'error';
            }
            else
            {
                // if selector exist validate it using token
                $tokenBin = hex2bin($validator);
                $tokenCheck = password_verify($tokenBin, $row["token"]);

                // Match form token with DB token
                if(!$tokenCheck)
                {
                    $error_log['sql'] = "Invalid Request!<br>Please re-submit Reset request using your Email";
                    $response['status'] = 'error';
                }
                elseif($tokenCheck === true)
                {
                    $userEmail = $row['email'];
                    
                    // Extra step to check if user is not deleted in between the process
                    $sql = "SELECT * FROM users WHERE email=?";
                    $stmt = mysqli_stmt_init($conn);
                    
                    if(!mysqli_stmt_prepare($stmt, $sql))
                    {
                        $error_log['sql'] = "Connection Failed! Please try again";
                        $response['status'] = 'error';
                    }
                    else
                    {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);
            
                        if(!$row = mysqli_fetch_assoc($result))
                        {
                            // User not found or doesn't exist
                            $error_log['sql'] = "Invalid Request!<br>Please re-submit Reset request using your Email";
                            $response['status'] = 'error';
                        }
                        else
                        {
                            // If no error then update password
                            $sql = "UPDATE users SET password=? WHERE email=?";
                            $stmt = mysqli_stmt_init($conn);
                            
                            if(!mysqli_stmt_prepare($stmt, $sql))
                            {
                                $error_log['sql'] = "Connection Failed! Please try again";
                                $response['status'] = 'error';
                            }
                            else
                            {
                                $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                                
                                mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $userEmail);
                                mysqli_stmt_execute($stmt);

                                // Delete password reset token and record from the table after successful reset
                                $sql = "DELETE FROM password_resets WHERE email=?";
                                $stmt = mysqli_stmt_init($conn);
                                
                                if(!mysqli_stmt_prepare($stmt, $sql))
                                {
                                    $error_log['sql'] = "Connection Failed! Please try again";
                                    $response['status'] = 'error';
                                }
                                else
                                {
                                    mysqli_stmt_bind_param($stmt, "s", $userEmail);
                                    mysqli_stmt_execute($stmt);
                                    $response['status'] = 'success';
                                }
                            }
                        }
                    }
                }
                else
                {
                    $error_log['sql'] = "Invalid Request!<br>Please re-submit Reset request using your Email";
                    $response['status'] = 'error';
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
    // If page is visited without clicking on login button
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}
?>