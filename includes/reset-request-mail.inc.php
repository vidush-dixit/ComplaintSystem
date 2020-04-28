<?php

header('Content-type: application/json');

$response = array();
$error_log = array();

if($_POST)
{
    require_once('./functions.inc.php');
    
    $email    = $_POST['resetPassEmail'];
    
    // Initial Validation and Error Handling
    if(!validationEmail($email))
    {
        $error_log['resetPassEmail'] = 'Invalid Email!';
    }
    // Initial Validation End

    // Continue if non-empty data
    if(count($error_log) == 0)
    {
        require_once('../config/db.php');
        
        // Check if user with entered email is registered or not
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
            mysqli_stmt_store_result($stmt);

            // Continue further if user with email is registered
            if(mysqli_stmt_num_rows($stmt) > 0)
            {
                $selector   = bin2hex(random_bytes(8));
                $token      = random_bytes(32);

                // Reset Url
                $resetPassUrl = $siteUrl."/changePassword.php?selector=" . $selector . "&validator=" . bin2hex($token);
                
                // expiry for token (reset-link)
                $tokenExpiry = date("U") + 1800;

                // Delete previous token for the user
                $sql = "DELETE FROM password_resets WHERE email=?";
                $stmt = mysqli_stmt_init($conn);
                
                if(!mysqli_stmt_prepare($stmt,$sql))
                {
                    $error_log['sql'] = "Oops! Something went Wrong. Try Again";
                    $response['status'] = 'error';
                }
                else
                {
                    mysqli_stmt_bind_param($stmt,"s",$email);
                    mysqli_stmt_execute($stmt);
                    
                    // After ensuring no token is there for current user
                    // Create a fresh token and entry in password_resets table
                    $sql = "INSERT INTO password_resets (email, selector, token, expires_at) VALUES (?,?,?,?)";
                    $stmt = mysqli_stmt_init($conn);
                
                    if(!mysqli_stmt_prepare($stmt,$sql))
                    {
                        $error_log['sql'] = "Oops! Something went Wrong. Try Again";
                        $response['status'] = 'error';
                    }
                    else
                    {
                        // Hash the token for security and insert the record into the table
                        $hashedToken = password_hash($token);
                        mysqli_stmt_bind_param($stmt,"ssss",$email, $selector, $hashedToken, $tokenExpiry);
                        mysqli_stmt_execute($stmt);

                        // Mailing to the user
                        $to = $email;

                        $subject = "Reset Password | ".$siteUrl;
                        $message = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" height=\"100%\" bgcolor=\"#FFFFFF\" width=\"100%\" style=\"max-width: 650px;\" id=\"bodyTable\">
                            <tr>
                                <td align=\"center\" valign=\"top\">
                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" id=\"emailContainer\" style=\"font-family:Arial; color: #333333;\">
                                        <!-- Logo -->
                                        <tr>
                                            <td align=\"left\" valign=\"top\" colspan=\"2\" style=\"border-bottom: 1px solid #CCCCCC; padding-bottom: 10px;\">
                                                <img alt=".$siteName." border=\"0\" src=".$siteUrl."/assets/images/logo.png\" title=".$siteName." class=\"sitelogo\" width=\"60%\" style=\"max-width:250px;\" />
                                            </td>
                                        </tr>
                        
                                        <!-- Title -->
                                        <tr>
                                            <td align=\"left\" valign=\"top\" colspan=\"2\" style=\"border-bottom: 1px solid #CCCCCC; padding: 20px 0 10px 0;\">
                                                <span style=\"font-size: 18px; font-weight: normal;\">FORGOT PASSWORD</span>
                                            </td>
                                        </tr>
                        
                                        <!-- Messages -->
                                        <tr>
                                            <td align=\"left\" valign=\"top\" colspan=\"2\" style=\"padding-top: 10px;\">
                                                <span style=\"font-size: 12px; line-height: 1.5; color: #333333;\">
                                                    We have sent you this email in response to your request to reset your password on ".$siteName.". After you reset your password, any credit card information stored in My Account will be deleted as a security measure.
                                                    <br/><br/>
                                                    To reset your password for <a href=".$siteUrl.">".$siteUrl."</a>, please follow the link below:
                                                    <a href=\"$resetPassUrl\">$resetPassUrl</a>
                                                    <br/><br/>
                                                    We recommend that you keep your password secure and not share it with anyone.If you feel your password has been compromised, you can change it by going to your ".$siteName." My Account Page and clicking on the \"Change Email Address or Password\" link.
                                                    <br/><br/>
                                                    If you need help, or you have any other questions, feel free to email info@".$siteName.".com, or call ".$siteName." customer service toll-free at xxx-xxx-xxxx.
                                                    <br/><br/>
                                                    ".$siteName." Customer Service
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>";

                        // From field of mail
                        $headers = "From: CMS <info@cms.com>\r\n";
                        // Reply address, in case user wants to reply
                        $headers .= "Reply-To: writpla@hi2.in\r\n";
                        // Allow html tags inside message body
                        $headers .= "Content-type: text/html\r\n";

                        // Send Mail
                        mail($to, $subject, $message, $headers);

                        $response['status'] = 'success';
                    }
                }
            }
            else
            {
                // If User with email not found do nothing
                // Good for Security
                // $error_log['resetPassEmail'] = "User not Registered";
                // $response['status'] = 'error';
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