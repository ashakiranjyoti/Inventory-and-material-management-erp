<?php session_start();

function checkLogin() {
    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: index.php'); // Redirect to login page
        exit;
    }
}
?>