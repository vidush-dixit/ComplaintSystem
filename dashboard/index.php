<?php
session_start();
// Show this page only if user is logged in
if (isset($_SESSION['userId']) && isset($_SESSION['userName']))
{
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">

  <title>CMS | Dashboard</title>

  <?php include_once('./styles.php');?>
  
</head>

<body class="dark-edition">

  <div class="wrapper">    
    <?php include_once('./sideNavbar.php');?>
    <div class="main-panel">
      <?php include_once('./topNavbar.php');?>
      <div class="content">

      <div class="d-flex justify-content-center">
        <div class="spinner-grow text-success mt-3 mx-auto" role="status" style="visibility: show;">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
        
      </div>
    </div>
  </div>
  
  <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
      <a href="#" data-toggle="dropdown">
        <i class="material-icons py-3">settings</i>
      </a>
      <ul class="dropdown-menu">
        <li class="header-title"> Sidebar Filters</li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger active-color">
            <div class="badge-colors ml-auto mr-auto">
              <span class="badge filter badge-purple active" data-color="purple"></span>
              <span class="badge filter badge-azure" data-color="azure"></span>
              <span class="badge filter badge-green" data-color="green"></span>
              <span class="badge filter badge-warning" data-color="orange"></span>
              <span class="badge filter badge-danger" data-color="danger"></span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="header-title">Images</li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-1.jpg" alt="">
          </a>
        </li>
        <li class="active">
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-2.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-3.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-4.jpg" alt="">
          </a>
        </li>
      </ul>
    </div>
  </div>
  <?php include_once('./scripts.php'); ?>
  
</body>
</html>

<?php
}
else
{
  header("Location: ../");
}
?>