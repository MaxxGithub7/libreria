<?php
session_start();
include_once __DIR__ . '/util.php';
include_once __DIR__ . '/Utenti.php';
include_once __DIR__ . '/Admin.php';

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('Location: https://localhost/libreria/login.php');
    exit;
}

$loggedInUser = \DataHandling\Admin::loginAdmin($_POST);
$_SESSION['username'] = $loggedInAdmin['username'];
$_SESSION['userId'] = $loggedInAdmin['id'];
header('Location: https://localhost/libreria');
exit;


$loggedInUser = \DataHandling\Utenti::loginUser($_POST);
$_SESSION['username'] = $loggedInUser['username'];
$_SESSION['userId'] = $loggedInUser['id'];
header('Location: https://localhost/libreria');
exit;