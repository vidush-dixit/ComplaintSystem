<!-- Login Modal -->
<!-- Modal -->
<div class="modal fade" id="signInModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header web-theme text-white">
            <h5 class="modal-title">Login Form</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="login-form">
                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="loginEmail">Email address</label>
                        <input type="email" name="loginEmail" class="form-control" id="loginEmail" placeholder="email@example.com" required autocomplete="current-email">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" name="loginPassword" class="form-control" id="loginPassword" placeholder="Password" required autocomplete="off">
                    </div>
                    
                    <!-- Remember Me checkbox -->
                    <div class="form-check">
                        <input type="checkbox" name="rememberMe" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            Remember me
                        </label>
                    </div>
                    
                    <!-- Login Submit Button -->
                    <button type="submit" name="login-submit" class="btn btn-primary mt-2 w-100">Sign in</button>
                </form>
            </div>

            <!-- External Links for Sign In and Reset Password -->
            <div class="modal-footer px-0">
                <div class="row w-100 text-md-left text-center">
                    <div class="col-12 col-md-6">
                        <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#resetPasswordModal">Forgot Password?</a>
                    </div>
                    <div class="col-12 col-md-6">
                        Don't have an Account?&nbsp;<a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SignUp Modal -->
<!-- Modal -->
<div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header web-theme text-white">
            <h5 class="modal-title">Sign Up Form</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="register-form">
                    <!-- Name -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating" for="signupName">Full Name</label>
                                <input type="text" name="signupName" id="signupName" class="form-control" placeholder="Name" required autocomplete="new-name">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating" for="signupEmail">Email address</label>
                                <input type="email" name="signupEmail" id="signupEmail" class="form-control" placeholder="example@domain.com" required autocomplete="new-email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating" for="signupPhone">Phone</label>
                                <input type="text" name="signupPhone" id="signupPhone" class="form-control" placeholder="Phone" required autocomplete="new-phone">
                            </div>
                        </div>
                    </div>

                    <!-- Password Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating" for="signupPassword">Password</label>
                                <input type="password" name="signupPassword" id="signupPassword" placeholder="Password" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating" for="repeatPassword">Confirm Password</label>
                                <input type="password" name="repeatPassword" id="repeatPassword" placeholder="Repeat Password" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Agree Terms Checkbox -->
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="agreeTerms" class="form-check-input" value="agree" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                Agree to terms and conditions
                            </label>
                        </div>
                    </div>
                    
                    <!-- Sign Up Submit Button -->
                    <button type="submit" name="signup-submit" class="btn btn-primary mt-2 w-100">Create Account</button>
                </form>
            </div>
            <div class="modal-footer mx-auto">
                Already have an Account?&nbsp;<a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signInModal">Sign In</a>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<!-- Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header web-theme text-white">
                <h5 class="modal-title">Reset Password Form</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>If you are a registered user, you will receive an email with instructions on how to reset your password.</p>
                <form method="post" id="mailResetPass-form">
                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="resetPassEmail">Email address</label>
                        <input type="email" name="resetPassEmail" class="form-control vw-100" id="resetPassEmail" placeholder="email@example.com" required autocomplete="reset-email">
                    </div>
                    <!-- Reset Password Submit Button -->
                    <button type="submit" name="mailResetPass-submit" class="btn btn-primary mt-2 w-100">Send mail to reset password</button>
                </form>
            </div>
        </div>
    </div>
</div>