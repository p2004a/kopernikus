<?php
  /**
   * @brief Główny skrypt systemu
   * 
   * Podstawowe funkcje do zarządzania systemem, powinien być includowany zawsze
   * gdy strona używa modułów z tego systemu.
   * Prefiks elementów w pliku: core
   * @author Marek "p2004a" Rusinowski
   * @file core.php
   */

  $core_debug = true;
  
  if ($core_debug) {
    $core_start_time = microtime(true);
  }

  if (!in_array($_SERVER['REQUEST_METHOD'], array("GET", "POST"))) {
    die();
  }
  
  if (!file_exists("./.sessions/")) {
    mkdir("./.sessions/");
  }
  session_save_path("./.sessions/");
  session_start();

  // parsing uri
  $core_params = array();
  if (isset($_GET['f']) && !is_array($_GET['f'])) {
    $core_params = explode("/", $_GET['f']);
    if (end($core_params) == "") array_pop($core_params);
    foreach ($core_params as $elem) {
      if (!preg_match("/^[a-z0-9_]{1,19}$/", $elem)) {
        $core_params = array();
        break;
      }
    }
  }
  
  /**
   * @brief Zmienna zawierająca base adres strony
   */
  $core_base_root = "http://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, -9);
  
  /**
   * @brief Tablica zawierająca ostrzeżenia
   * @see core_warning()
   */
  $core_warnings = array();

  /**
   * @brief Zgłoszenie błędu
   * 
   * Funkcja służy do ładnego i czystego obwieszczenia użytkownikowi, że gdzieś
   * wystąpił błąd i dalsze wykonanie jego żądania jest niemożliwe. Na końcu 
   * swojego działania wywołuję funkcje exit().
   * @param $str Zawiera treść błędu którą należy obwieścić użytkownikowi.
   */
  function core_error($str) {
    global $core_debug;
    $htmlerror = new HTMLPage();
    $htmlerror->addBody(new HTMLString("ERROR: $str"));
    echo $htmlerror->render();
    if ($core_debug) {
      echo "<pre>";
      debug_print_backtrace();
      echo "</pre>";
    }
    exit();
  }
  
  /**
   * @brief Zgłoszenie ostrzeżenia
   *
   * Głównie dla programisty, pozwala na wyrzucenie ostrzeżenia które następnie
   * zostanie wyświetlone w komentarzu HTML na końcu wygenerowanej strony.
   * @param $str Zawiera treść ostrzeżenia.
   */
  function core_warning($str) {
    global $core_warnings;
    array_push($core_warnings, $str);
  }

  /**
   * @brief Renderowanie strony
   *
   * Powinna być wywoływana jako ostatnia, generuje treść strony przygotowaną
   * przez system.
   */
  function core_render() {
    global $core_warnings, $html, $core_debug;
    echo $html->render();
    if (!empty($core_warnings)) {
      echo "\n<!--\n";
      foreach ($core_warnings as $str) {
        echo "$str\n";
      }
      echo "-->";
    }
    if ($core_debug) {
      printf('<div style="text-align:center; width:100%%; color:white;">te: %.3fs dbnc: %d dbnq: %d</div>', 
       microtime(true) - $GLOBALS['core_start_time'], 
       $GLOBALS['db_num_connections'], 
       $GLOBALS['db_num_queries']);
    }
  }
  
  require("html.php");
  require("database.php");
  
  if (file_exists("config.php")) {
    require("config.php");
  } else {
    require("install.php");
    die();
  }
  
  require("auth.php");
  require("form.php");
?>
