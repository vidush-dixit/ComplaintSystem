<?php
    // Reset and destroy all session variables
    session_start();
    session_unset();
    session_destroy();
    // Return back to index/home page
    header("Location: ../");
?>