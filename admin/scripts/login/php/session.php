<?php


session_start();


function logged_in() {
    return isset($_SESSION['username']);
}


function confirm_logged_in() {
    if (!logged_in()) {
        ?>
        <script type="text/javascript">
        window.location = "login.php";
        </script>
        <?php
    }
}


function set_username($username) {
    $_SESSION['username'] = $username;
}


?>