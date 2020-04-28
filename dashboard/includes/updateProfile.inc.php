<?php
header('Content-type: application/json');
session_start();
    
$response = array();
$error_log = array();

// Check if page is visited after clicking form button or not
if($_POST)
{
    $status = $_POST['action'];
    
    // ***********************
    // Update Profile Request
    // ***********************
    if ( $status == 'update' )
    {
        require_once('../../includes/functions.inc.php');

        $rowId    = $_POST['updateProfileIdentity'];
        $newName  = $_POST['profileName'];
        $newPhone = $_POST['profilePhone'];
        $newDP    = $_FILES['profileImage'];
        
        // If any image is selected
        if( !empty($newDP['name']) )
            $newDP = $_FILES['profileImage'];
        else
            $newDP = null;
        
        // Initial Validation and Error Handling
        if( !validationName($newName) )
        {
            $error_log['profileName'] = 'Invalid Name!';
        }
        if( !validationPhone($newPhone) )
        {
            $error_log['profilePhone'] = 'Invalid Phone!';
        }
        if( $newDP != null && !validationProfileImage($newDP) )
        {
            $error_log['profileImage'] = "Must be JPEG/PNG with max. size and Dimensions 800KB (800x800)";
        }
        // Initial Validation End

        if( count($error_log) == 0 )
        {
            require_once('../../config/db.php');
            $dpName = "";

            if( $newDP != null )
            {
                // File Properties
                $fileName         = $newDP['name'];
                $fileTmpName      = $newDP['tmp_name'];
                $fileParts        = explode('.',$fileName);
                $fileExt          = strtolower(end($fileParts));
                $fileUploadName   = "profile-".$rowId.".".$fileExt;
                
                // Location in which file is to be stored
                $location         = "../../assets/img/faces/".$fileUploadName;
                move_uploaded_file($fileTmpName, $location);

                $dpName = $fileUploadName;
            }
            else
            {
                $sql    = "SELECT * FROM `users` WHERE `id`='".$rowId."'";
                $result = mysqli_query ($conn, $sql);
                $row    = mysqli_fetch_assoc($result);
                
                $dpName  = $row['profile_picture'];
                
                // Free result set
                mysqli_free_result($result);
            }
            // if not then insert user details to the table
            $sql = "UPDATE `users` SET `name`=?,`phone`=?,`profile_picture`=?,`updated_at`=NOW() WHERE `id`=?";
            
            $stmt = mysqli_stmt_init($conn);

            if( !mysqli_stmt_prepare($stmt, $sql) )
            {
                $response['status'] = 'error';
                $error_log['sql'] = 'Connection Failed! Please try again';
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "ssss", $newName, $newPhone, $dpName, $rowId);
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

    // ***********************
    // Update Password Request
    // ***********************
    else if( $status == 'changePassword' )
    {
        require_once('../../includes/functions.inc.php');

        $rowId   = $_POST['updateProfileIdentity'];
        $oldPass = $_POST['profileOldPassword'];
        $newPass = $_POST['profileNewPassword'];
        $rptPass = $_POST['profileRepeatPassword'];
        
        // Initial Validation and Error Handling
        if( !validationPassword($oldPass) )
        {
            $error_log['profileOldPassword'] = 'Must be min. 8 characters!';
        }
        if( !validationPassword($newPass) )
        {
            $error_log['profileNewPassword'] = 'Must be min. 8 characters!';
        }
        if( $newPass == $oldPass )
        {
            $error_log['profileNewPassword'] = "Must not match Old Password";
        }
        if( !validationRepeatPassword($newPass, $rptPass) )
        {
            $error_log['profileRepeatPassword'] = 'Must match Password!';
        }
        // Initial Validation End

        if( count($error_log) == 0 )
        {
            require_once('../../config/db.php');

            // Fetch data from users table to match old password
            $sql = "SELECT * FROM `users` WHERE `id`=? LIMIT 1";
            $stmt = mysqli_stmt_init($conn);
            
            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                $error_log['sql'] = "Oops! Something went Wrong. Try Again";
                $response['status'] = 'error';
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "s", $rowId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                
                // Match User Entered Password with Database old Password
                $pwdCheck = password_verify($oldPass, $row['password']);
                if(!$pwdCheck)
                {
                    // Wrong Password. So send back to modal with error
                    $error_log['profileOldPassword'] = "Incorrect Password!";
                    $response['status'] = 'error';
                }
                else if($pwdCheck)
                {
                    // if old Password is correct update password details
                    $sql = "UPDATE `users` SET `password`=?,`updated_at`=NOW() WHERE `id`=?";
                    $stmt = mysqli_stmt_init($conn);

                    if( !mysqli_stmt_prepare($stmt, $sql) )
                    {
                        $response['status'] = 'error';
                        $error_log['sql'] = 'Connection Failed! Please try again';
                    }
                    else
                    {
                        $hashedNewPwd = password_hash($newPass, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, "ss", $hashedNewPwd, $rowId);
                        mysqli_stmt_execute($stmt);

                        $response['status'] = 'success';
                    }
                }
                else
                {
                    // Just in case if pwdCheck is not boolean. So return to index page login modal stating Wrong Password
                    $error_log['profileOldPassword'] = "Incorrect Password!";
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
    }
    else
    {
        // Protection from invalid input
        $response['status'] = 'error';
        $error_log['sql'] = "Illegal Submission";
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