<?php
namespace App;

class Libreria
{

    private const BASIC_URL = 'https://localhost/libreria/';

  

    public static function create($data)
    {

        if (! isset($data['libro'])) {
            header('Location: ' . self::BASIC_URL . '?stato=err&id_err=1');
        }

        $titolo_libro = self::sanitize($data['libro']);

        $db = connect();

        $query = $db->prepare('INSERT INTO libri(titolo) VALUES(?)');
        $query->bind_param('s', $titolo_libro);
        $query->execute();

        if ($query->affected_rows <= 0) {
            foreach ($query->error_list as $error) {
                error_log('Errore MySQL: ' . $error['error']);
            }
            header('Location: ' . self::BASIC_URL . '?stato=ko');
            exit();
        }

        $db->close();

        header('Location: ' . self::BASIC_URL . '?stato=ok');
        exit();
    }

    
    public static function read($id = null)
    {

        $db = connect();

        $query = $db->query('SELECT * FROM libri ORDER BY titolo ASC');

        $results = array();

        while ($libro = $query->fetch_assoc()) {
            $results[] = $libro;
        }

        return $results;
    }

    private static function updateAll()
    {

        $db = connect();

        $query = $db->query('UPDATE libri SET stato = 1, data_aggiornamento = NOW()');

        $stato_upd = ( $query->num_rows > 0 ) ? 'ok' : 'ko';

        header('Location: ' . self::BASIC_URL . '?stato=' . $stato_upd);
        exit();
    }

    public static function update($id, $args)
    {

        if (is_null($id)) {
            self::updateAll();
        }

        if (intval($id) === 0) {
            error_log('Errore PHP: ID non valido.');
            header('Location: ' . self::BASIC_URL . '?stato=ko');
        }

        $db = connect();

        if (isset($args['stato'])) {
            if (! is_int($args['stato']) && ! ( $args['stato'] === 0 || $args['stato'] === 1 )) {
                error_log('Errore PHP: Stato non valido.');
                header('Location: ' . self::BASIC_URL . '?stato=ko');
            }

            $stato = ( intval($args['stato']) ) ? 0 : 1;

            $query = $db->prepare('UPDATE libri SET stato = ?, data_aggiornamento = NOW() WHERE id = ?');
            $query->bind_param('ii', $stato, $id);
            $query->execute();

            if ($query->affected_rows <= 0) {
                foreach ($query->error_list as $error) {
                    error_log('Errore MySQL: ' . $error['error']);
                }
                header('Location: ' . self::BASIC_URL . '?stato=ko');
                exit();
            }
        }

        if (isset($args['id'])) {
            if (intval($args['id']) === 0) {
                error_log('Errore PHP: ID non valido.');
                header('Location: ' . self::BASIC_URL . '?stato=ko');
            }

            $titolo_libro = self::sanitize($args['titolo']);

            $query = $db->prepare('UPDATE libri SET stato = 0, titolo = ? WHERE id = ?');
            $query->bind_param('si', $titolo_libro, $args['id']);
            $query->execute();

            if ($query->affected_rows <= 0) {
                foreach ($query->error_list as $error) {
                    error_log('Errore MySQL: ' . $error['error']);
                }
                header('Location: ' . self::BASIC_URL . '?stato=ko');
                exit();
            }
        }

        $db->close();

        header('Location: ' . self::BASIC_URL . '?stato=ok');
        exit();
    }

    public static function delete($id)
    {
        if (! is_null($id) || intval($id) === 0) {
            error_log('Errore PHP: ID non valido.');
            header('Location: ' . self::BASIC_URL . '?stato=ko');
        }
        $db = connect();

        if (is_null($id)) {
            $query_stmt = 'DELETE FROM libri';
            $query      = $db->query($query_stmt);
        } else {
            $query_stmt = 'DELETE FROM libri WHERE id = ?';
            $query      = $db->prepare($query_stmt);
            $query->bind_param('i', $id);
            $query->execute();
        }

        $stato_canc = ( $query->affected_rows > 0 ) ? 'ok' : 'ko';

        header('Location: ' . self::BASIC_URL . '?statocanc=' . $stato_canc);
        exit();
    }

    protected static function sanitize($field)
    {
        $field = trim($field);
        $field = filter_var($field, FILTER_SANITIZE_ADD_SLASHES);
        $field = filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS);
        return $field;
    }
}
