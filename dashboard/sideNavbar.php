<div class="sidebar" data-color="purple" data-background-color="black" data-image="../assets/img/sidebar-2.jpg">
    <div class="logo">
        <a class="navbar-brand" href="../"><img src="../assets/img/favicon.png" alt="CMS" title="CMS" style="width: 50%; margin: 0 25%;"></a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item active">
                <a class="nav-link" href="./#">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#profile">
                    <i class="material-icons">account_circle</i>
                    <p>Profile</p>
                </a>
            </li>
            <?php
                if ( $_SESSION['userType'] == 'admin' )
                {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="#users">
                    <i class="material-icons">group</i>
                    <p>Users</p>
                </a>
            </li>
            <?php
                }
                if ( $_SESSION['userType'] == 'admin' )
                {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="#departments">
                    <i class="material-icons">category</i>
                    <p>Departments</p>
                </a>
            </li>
            <?php
                }
                if ( $_SESSION['userType'] != 'user' )
                {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="#announcements">
                    <i class="material-icons">add_alert</i>
                    <p>Announcements</p>
                </a>
            </li>
            <?php
                }
            ?>
            <li class="nav-item">
                <a class="nav-link" href="#complaints">
                    <i class="material-icons">content_paste</i>
                    <p>Complaints</p>
                </a>
            </li>
        </ul>
    </div>
</div>