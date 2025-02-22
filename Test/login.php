<?php
session_start();
if (isset($_SESSION["user_id"])) {
    session_destroy();
}
header("Location: logout_page.php"); // Redirect to a stylish logout confirmation page
exit();
?>
