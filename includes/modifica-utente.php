<?php
require_once __DIR__ . '/globals.php';

$args = array();

if (isset($_GET['stato'])) {
    $args['stato'] = $_GET['stato'];
}

if (isset($_POST['utenti'])) {
    $args['username'] = $_POST['utenti'];
}

if (isset($_POST['id'])) {
    $args['id'] = $_POST['id'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = null;
}


\App\Libreria\Utenti::update($id, $args);