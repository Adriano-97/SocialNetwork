<?php
include ("classes/DB.php");
include ("classes/Login.php");

if (Login::isLoggedIn()) {
    echo "Logged in";
    echo Login::isLoggedIn();
} else {
    echo "Not logged in";
}
?>
