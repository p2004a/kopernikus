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

  session_start();
  
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
    $htmlerror = new HTMLPage();
    $htmlerror->addBody(new HTMLString("ERROR: $str"));
    echo $htmlerror->render();
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
    global $core_warnings, $html;
    echo $html->render();
    if (!empty($core_warnings)) {
      echo "\n<!--\n";
      foreach ($core_warnings as $str) {
        echo "$str\n";
      }
      echo "-->";
    }
  }
  
  require("html.php");
  require("config.php");
  require("database.php");
?>
