<?php
  /**
   * @brief Funkcie logów
   * 
   * Funkcje i klasy potrzebne do tworzenia logów
   * Prefiks elementów w pliku: log
   * @author Marek "p2004a" Rusinowski
   * @file form.php
   */
   
   /**
    * @brief Tworzy log
    *
    * @param $msg Treść loga
    */
  function log_msg($msg) {
    $user = auth_who();
    db_query("INSERT INTO logs (user_id, message, time) VALUES ({$user['user_id']}, '" . db_esc_str($msg) . "', '" . date('Y-m-d H:i:s') . "')");
  }
?>
