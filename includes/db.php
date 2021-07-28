<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'libreriadb');

if ($mysqli->connect_errno) {
    echo 'Connessione al database fallita: ' . $mysqli->connect_error;
    exit();
}

$query = $mysqli->prepare("SELECT libri.ID, Titolo, Autore, Utenti_id FROM libri
            JOIN utenti
              ON libri.ID = utenti.utente_id
            WHERE utenti.id_utente = ?");
$query->bind_param('i', $_SESSION['userId']);
$query->execute();
$result = $query->get_result();

$libri = array();
if ($result->num_rows > 0) {
  while ($libri = $result->fetch_assoc()) {
    $libri[] = $libro;
  }
}