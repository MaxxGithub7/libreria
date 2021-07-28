<?php
//phpcs:ignorFile
require_once __DIR__ . '/includes/globals.php';
$libri = \App\Libreria::read();
if (isset($_GET['id'])) {
    $libri_to_mod = null;
    for ($i = 0; $i < count($libri); $i++) {
        if ($libri[ $i ]['id'] === $_GET['id']) {
            $libro_to_mod = $libri[ $i ];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Libreria</title>
  <link rel="stylesheet" href="./style/style.css" media="all" />
</head>
<body>
  <header>
    <h1>Libreria</h1>
    <div class="libro-bulk-action">
      <a href="/libreria/includes/aggiungi-libro.php">Aggiungi libro</a>
      <a href="/libreria/includes/cancella-libro.php">Cancella libro</a>
      <a href="/libreria/includes/modifica-libro.php">Modifica libro</a>
    </div>
  </header>
  <main>
    <div class="libro-form">
      <?php
        $form_config = array(
            'action'       => ( isset($_GET['id']) ) ? 'includes/modifica-libro.php' : 'includes/aggiungi-libro.php' ,
            'value'        => ( isset($_GET['id']) ) ? $libri_to_mod['titolo'] : '',
            'button_label' => ( isset($_GET['id']) ) ? 'Modifica Libro' : 'Aggiungi Libro',
            'hidden_field' => ( isset($_GET['id']) ) ? "<input type='hidden' name='id' id='id' value='{$_GET['id']}' />"
            : '',
        );
        ?>
      <form method="POST" action="<?php echo $form_config['action']; ?>">
        <label>Libreria:</label>
        <div>
          <input
            type="text"
            name="libro"
            id="libro"
            required
            autocomplete="off"
            placeholder="Vuoi aggiungere un nuovo libro?"
            value="<?php echo $form_config['value']; ?>"
          />
          <?php echo $form_config['hidden_field']; ?>
          <input type="submit" value="<?php echo $form_config['button_label']; ?>">
        </div>
      </form>
    </div>
    <div class="libri">
      <ul class="libreria">
        <?php
        if (count($libri) === 0) {
            echo '<li class="libreria__item libreria__item--no-item">Nessun libro</li>';
        } else {
            foreach ($libri as $libro) {
                $isAvailable = ( $libro['stato'] === '0' ) ? false : true;
                $stato      = ( $isAvailable ) ? '🟩' : '🟥';
                $className  = ( $isAvailable ) ? 'libreria__item libreria__item--available' : 'libreria__item';

                echo "<li class='{$className}'>";
                echo '<a href="/libreria/includes/modifica-libro.php?id=' . $libro['id'] . '&stato=' . $libro['stato'] .
                '">' . $stato . '</a>';
                echo '<span>' . $libro['titolo'] . '</span>';
                if (is_null($libro['data_completamento'])) {
                    echo '<span>' . $libro['data_creazione'] . '</span>';
                } else {
                    echo '<span>' . $libro['data_completamento'] . '</span>';
                }
                echo '<div class="libreria__action"><span><a href="/libreria/includes/cancella-libro.php?id=' .
                $libro['id'] . '">❌</a></span><span><a href="/libreria/?id=' . $libro['id'] . '">✏</a></span></div>';
                echo '</li>';
            }
        }
        ?>
      </ul>
    </div>
  </main>
</body>
</html>
