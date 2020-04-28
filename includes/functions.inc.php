<?php

function validationName( $name )
{
    if( empty($name) || !preg_match("/^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/",$name) || strlen($name) > 100 )
        return false;
    return true;
}
function validationPhone( $phone )
{
    if( empty($phone) || !preg_match("/^[0-9]{10}$/",$phone) )
        return false;
    return true;
}
function validationEmail( $email )
{
    if( empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL) )
        return false;
    return true;
}

function validationPassword( $password )
{
    if( strlen($password) < 8 )
        return false;
    return true;
}

function validationRepeatPassword( $password, $repeatPassword )
{
    if( empty( $repeatPassword) || $repeatPassword != $password )
        return false;
    return true;
}

function validationProfileImage( $fileImage )
{
    $fileName       = $fileImage['name'];
    $fileTempName   = $fileImage['tmp_name'];
    $fileParts      = explode('.',$fileName);
    $fileExt        = strtolower(end($fileParts));
    $fileError      = $fileImage['error'];
    $fileType       = $fileImage['type'];
    // Max Size 800KB
    $fileSize       = $fileImage['size'];
    // array with the allowed extension name values
    $allowedExt     = array('jpg', 'jpeg', 'png');
    // Image Dimensions - Max dimensions 600x600
    $fileWidth      = getimagesize($fileTempName)[0];
    $fileHeight     = getimagesize($fileTempName)[1];
    
    if( $fileError > 0 || !in_array($fileExt, $allowedExt) || ( $fileSize > 800000 || ( $fileWidth > 800 || $fileHeight > 800 ) ) )
        return false;
    else
        return true;
}
?>