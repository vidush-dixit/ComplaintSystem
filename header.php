<?php
    $siteUrl = $_SERVER['HTTP_HOST'];
    $siteName = 'CMS';

    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/favicon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    
    <title>CMS | Welcome</title>
    
    <?php include_once('./styles.php');?>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-transparent mx-auto py-2 web-theme">
            <!-- Company Name -->
            <a class="navbar-brand" href="<?php $siteUrl?>"><img src="./assets/img/favicon.png" alt="CMS" title="CMS" style="width: 80px;"></a>
            <!-- Account Button & Form -->
            <div class="btn-group mr-2 ml-auto order-md-10">
                <?php
                if(!isset($_SESSION['userId']))
                {
                    echo '<!-- Sign In Button modal trigger -->
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#signInModal">
                        Sign in
                    </button>
                    
                    <!-- Sign In Button modal trigger -->
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#signUpModal">
                        Sign Up
                    </button>';
                }
                else
                {
                    echo '<!-- User Account Dropdown Menu -->
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons align-middle">person</i>&nbsp;'.$_SESSION['userName'].'
                    </button>
                    
                    <div class="dropdown-menu dropdown-menu-right mt-2 bg-dark">
                        <a class="dropdown-item" href="./dashboard/">
                            <svg height="16px" width="16px" class="align-middle" viewBox="0 0 511 512" xmlns="http://www.w3.org/2000/svg"><path d="m197.332031 0h-160c-20.585937 0-37.332031 16.746094-37.332031 37.332031v96c0 20.589844 16.746094 37.335938 37.332031 37.335938h160c20.589844 0 37.335938-16.746094 37.335938-37.335938v-96c0-20.585937-16.746094-37.332031-37.335938-37.332031zm0 0" fill="#2196f3"/><path d="m197.332031 213.332031h-160c-20.585937 0-37.332031 16.746094-37.332031 37.335938v224c0 20.585937 16.746094 37.332031 37.332031 37.332031h160c20.589844 0 37.335938-16.746094 37.335938-37.332031v-224c0-20.589844-16.746094-37.335938-37.335938-37.335938zm0 0" fill="#1976d2"/><path d="m474.667969 341.332031h-160c-20.589844 0-37.335938 16.746094-37.335938 37.335938v96c0 20.585937 16.746094 37.332031 37.335938 37.332031h160c20.585937 0 37.332031-16.746094 37.332031-37.332031v-96c0-20.589844-16.746094-37.335938-37.332031-37.335938zm0 0" fill="#2196f3"/><path d="m474.667969 0h-160c-20.589844 0-37.335938 16.746094-37.335938 37.332031v224c0 20.589844 16.746094 37.335938 37.335938 37.335938h160c20.585937 0 37.332031-16.746094 37.332031-37.335938v-224c0-20.585937-16.746094-37.332031-37.332031-37.332031zm0 0" fill="#1976d2"/></svg>
                            &nbsp;Dashboard</a>
                        <form action="./includes/logout.inc.php" method="post">
                            <button type="submit" name="logout-submit" class="dropdown-item"><svg height="18px" width="18px" class="align-middle" viewBox="0 0 511 512" xmlns="http://www.w3.org/2000/svg"><path d="m298.667969 277.335938c-35.285157 0-64-28.714844-64-64 0-35.285157 28.714843-64 64-64h42.664062v-85.332032c0-35.285156-28.714843-63.99999975-64-63.99999975h-229.332031c-7.019531 0-13.589844 3.45312475-17.578125 9.23437475-3.96875 5.78125-4.863281 13.144531-2.347656 19.691407l154.667969 405.335937c3.136718 8.277344 11.070312 13.738281 19.925781 13.738281h74.664062c35.285157 0 64-28.714844 64-64v-106.667968zm0 0" fill="#2196f3"/><path d="m397.164062 318.382812c-7.957031-3.308593-13.164062-11.09375-13.164062-19.714843v-64h-85.332031c-11.777344 0-21.335938-9.554688-21.335938-21.332031 0-11.777344 9.558594-21.332032 21.335938-21.332032h85.332031v-64c0-8.621094 5.207031-16.40625 13.164062-19.714844 7.976563-3.304687 17.152344-1.46875 23.25 4.632813l85.335938 85.332031c8.339844 8.339844 8.339844 21.824219 0 30.164063l-85.335938 85.335937c-6.097656 6.097656-15.273437 7.933594-23.25 4.628906zm0 0" fill="#607d8b"/><path d="m184.449219 44.84375-128.191407-42.730469c-28.929687-8.894531-56.257812 12.460938-56.257812 40.554688v384c0 18.242187 11.605469 34.519531 28.886719 40.492187l128.167969 42.730469c4.714843 1.449219 9.046874 2.113281 13.613281 2.113281 23.53125 0 42.664062-19.136718 42.664062-42.667968v-384c0-18.238282-11.605469-34.515626-28.882812-40.492188zm0 0" fill="#64b5f6"/></svg>&nbsp;Sign out</button>
                        </form>
                    </div>
                    <!-- Account Dropdown Menu End -->';
                }
                ?>
            </div>

            <!-- Navbar Hamburger Menu Toggle Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto w-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php $siteUrl?>#home">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php $siteUrl?>#announcements">Announcements</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="<?php $siteUrl?>#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php $siteUrl?>#contact">Contact</a>
                    </li>
                </ul>
                <input class="form-control w-100 mx-sm-2" type="search" placeholder="Search" aria-label="Search">
            </div>
        </nav>
    </header>
    <?php
        // Login and Signup Modals
        include_once('./modals.php');
    ?>