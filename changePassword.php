<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Reset Password</title>
    <?php include_once('./styles.php');?>
    <style>
        html, body {min-height: 100%;}
        body {
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="mt-5 pt-lg-5 pt-md-3 h-100 w-100" style="background: linear-gradient(to top right, #870000, #4a00e0, #190a05);">
    <div class="container row w-100 h-100 mx-auto pt-5">
        <?php
            if( (isset($_GET['selector']) && isset($_GET['validator'])) && (!empty($_GET['selector']) && !empty($_GET['validator'])))
            {
                $selector = $_GET['selector'];
                $validator = $_GET['validator'];

                if( !ctype_xdigit($selector) && !ctype_xdigit($validator) )
                {
                    // If Url values are valid then show form
                    ?>
                    <!-- Reset Password Form -->
                    <div class="card col-10 col-lg-6 mx-auto p-0">
                        <h5 class="card-header web-theme">Reset Password Form</h5>
                        <div class="card-body">
                            <form method="post" id="resetPass-form">
                                <!-- Hidden inputs to validate reset password request-->
                                <input type="hidden" name="resetSelector" value="<?php echo $selector?>">
                                <input type="hidden" name="resetValidator" value="<?php echo $validator?>">
                                
                                <!-- New Password -->
                                <div class="form-group row">
                                    <label for="newPassReset" class="col-sm-3 col-form-label">New Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="resetNewPass" class="form-control" id="newPassReset" placeholder="Enter New Password" required autocomplete="off">
                                    </div>
                                </div>
                                <!-- Repeat Password -->
                                <div class="form-group row">
                                    <label for="repeatPassReset" class="col-sm-3 col-form-label">Repeat Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="resetRepeatPass" class="form-control" id="repeatPassReset" placeholder="Enter Repeat Password" required autocomplete="off">
                                    </div>
                                </div>

                                <!-- Reset Password Submit Button -->
                                <button type="submit" name="resetPass-submit" class="btn btn-success w-100 mt-2">Reset Password</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            else
            {
                header("Location: ./");
                exit();
            }
        ?>
        
    </div>
    <?php include_once('./scripts.php');?>
</body>
</html>