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
    $db_db = @mysql_connect($conf_db['hostname'], $conf_db['username'], 
     $conf_db['password']);
    if (!$db_db) {
      core_error("Couldn't connect to database.");
    }
    $db_selected = @mysql_select_db($conf_db['dbname'], $db_db);
    if (!$db_selected) {
      core_error("Couldn't select database.");
    } 
  }
  
  /**
   * @brief Zamyka połączenie z bazą
   * @see db_connect()
   */
  function db_close() {
    global $db_db;
    if ($db_db != NULL) {
      @mysql_close($db_db);
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
   * @return Wynik zapytania
   */
  function db_query($query) {
    global $db_db, $db_num_queries;
    $closed = $db_db == NULL;
    if ($closed) {
      db_connect();
    }
    ++$db_num_queries;
    $result = @mysql_query($query);
    if (!$result) {
      core_error("Executed incorrect query to databese.");
    }
    $out = true;
    if (preg_match("/^SELECT.*$/", $query)) {
      $out = array();
      while ($row = mysql_fetch_assoc($result)) {
        array_push($out, $row);
      }
      mysql_free_result($result);
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
