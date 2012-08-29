<?php
  /**
   * @brief Strona główna
   * 
   * Skrypt powinien być uruchomiony raz, wtedy gdy strona pierwszy raz jest 
   * uruchamiana na danym serwerze. Dzieje się to automatycznie gdy core.php nie
   * wykryje pliku config.php który to plik ten skrypt tworzy.
   * plik moze zostać usunięty po instalacji.
   * @author Marek "p2004a" Rusinowski
   * @file install.php
   */
  
  foreach ($_POST as $key => $value) {
    ${$key} = $value; 
  }
  if (isset($db_login) && isset($db_pass) && isset($db_server) && isset($db_database)) {
    file_put_contents("config.php", "<?php \$conf_db = array('username' => '$db_login', 'password' => '$db_pass', 'dbname' => '$db_database', 'hostname' => '$db_server'); ?>");
    require("config.php");
    $queries = explode(";", file_get_contents("database.sql"));
    foreach ($queries as $query) {
      if (trim($query) != "") {
        db_query($query);
      }
    }
    $html->addBody(new HTMLFromString('<h1>Instalacja zakońcona powodzeniem. Odśwież stronę.</h1>'));
  } else {
    $html->addBody(new HTMLFromString('
      <h1>Formularz instalacji systemu.</h1>
      <form method="POST">
        Login do bazy: <input type="text" name="db_login" /><br />
        Hasło do bazy: <input type="password" name="db_pass" /><br />
        Adres bazy: <input type="text" name="db_server" /><br />
        Nazwa bazy: <input type="text" name="db_database" /><br />
        <input type="submit" />
      </form>
    '));
  }
  
  core_render();
?>
