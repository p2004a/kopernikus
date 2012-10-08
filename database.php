<?php
  /**
   * @brief Skrypt obsługi bazy danych
   * 
   * Znajdują się tutaj funkcje odpowiedzialne za łączenie z bazą danych strony, 
   * filtrowanie danych, logowanie połączeń, błędów i łatwą integrację z resztą 
   * systemu.
   * Prefiks elementów w pliku: db
   * @author Marek "p2004a" Rusinowski
   * @file database.php
   */
   
  /**
   * @brief Połączenie z bazą
   *
   * Jeśli połączenie nie jest aktywne jej wartość to NULL
   */
  $db_db = NULL;

  /**
   * @brief Liczba wykonanych dotychczas zapytań do bazy danych
   */
  $db_num_queries = 0;
  
  /**
   * @brief Liczba dotychczas ustanowionyh połączeń z bazą danych
   */
  $db_num_connections = 0;
  
  /**
   * @brief Ustanawia połączenie z bazą
   *
   * Próbuje ustanowić połączenie z bazą danych, gdy się nie uda informuje o tym
   * rdzeń systemu poprzez wywołanie @link core_error() @endlink.
   * @see core_error()
   * @see db_close()
   */
  function db_connect() {
    global $conf_db, $db_db, $db_num_connections;
    if ($db_db != NULL) {
      core_warning("Tried to open a connection to the database when connection "
       ."was already opened.");
      return;
    }
    ++$db_num_connections;
    $db_db = @mysqli_connect($conf_db['hostname'], $conf_db['username'], 
     $conf_db['password'], $conf_db['dbname']);
    if (!$db_db) {
      core_error("Couldn't connect to database. " . mysqli_connect_error());
    }
    if (!@mysqli_set_charset($db_db, "utf8")) {
      core_error("Couldn't load character set utf8. " . mysqli_error($db_db));
    }
  }
  
  /**
   * @brief Zamyka połączenie z bazą
   * @see db_connect()
   */
  function db_close() {
    global $db_db;
    if ($db_db != NULL) {
      @mysqli_close($db_db);
      $db_db = NULL;
    } else {
      core_warning("Tried to close not opened connection to the database.");
    }
  }
  
  /**
   * @brief Wykonuje zapytanie do bazy danych
   *
   * Wywołuje zapytanie podane w parametrze próbując wykorzystać otwarte
   * połączaenie z bazą. Jęśli połączenie nie jest ustanowione tworzy je
   * a po zakończeniu wywoływania zapytania zamyka połączenie z bazą.
   * Jeśli nie uda się wykonać zapytania wywoływana jest @link core_error()
   * @endlink. Funkcja zwraca true po wykonaniu zapytania, natomiast
   * gdy wywołane jest zapytanie SELECT zwraca jako wynik tablicę talbic
   * asocjacyjnych zawierającą wynik zapytania.
   * @param $query Zapytanie do bazy danych
   * @param $multi_query true jeśli kilka zapytań odzielonych średnikami
   * @return Wynik zapytania
   */
  function db_query($query, $multi_query = false) {
    global $db_db, $db_num_queries;
    $closed = $db_db == NULL;
    if ($closed) {
      db_connect();
    }
    ++$db_num_queries;
    if (!$multi_query) {
      $result = @mysqli_query($db_db, $query);
      if (!$result) {
        core_error("Executed incorrect query to databese. " . mysqli_error($db_db));
      }
      $out = true;
      if (preg_match("/^SELECT.*$/", str_replace("\n", " ", $query))) {
        $out = array();
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($out, $row);
        }
        mysqli_free_result($result);
      }
    } else {
      $result = @mysqli_multi_query($db_db, $query);
      if (!$result) {
        core_error("Executed incorrect multiquery to databese. " . mysqli_error($db_db));
      }
      $out = true;
    }
    if ($closed) {
      db_close();
    }
    return $out;
  }
  
  /**
   * @brief Dodaje znaki unikowe do zapytania
   *
   * @param $query Zapytanie do bazy danych
   * @return Wynik zapytania
   */
  function db_esc_str($str) {
    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $str);
  }
?>
