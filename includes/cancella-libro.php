<?php
require_once __DIR__ . '/globals.php';

$id = ( isset($_GET['id']) ) ? $_GET['id'] : null;

\App\Libreria::delete($id);