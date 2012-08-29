<?php
  /**
   * @brief Autoryzacja użytkowników
   * 
   * Podstawowe funkcje do autoryzacji uzytkowników, logowania i tym podobnych
   * czynności związanych z ochorna dostępu
   * Prefiks elementów w pliku: auth
   * @author Marek "p2004a" Rusinowski
   * @file auth.php
   */
  
  // jeśli ip nie pasuje badz użytkownik dopiero wszedł, logujemy jako gościa
  if (!isset($_SESSION['user_id']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    $_SESSION['user_id'] = db_query("SELECT user_id FROM users WHERE login = 'guest'")[0]['user_id'];
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
  }
  
  /**
   * @brief Loguje użytkownika
   *
   * @param $login login użytkownika, musi pasować do wyrażenia "/^[A-Za-z0-9_]{3,19}$/"
   * @param $password hasło użytkownika
   * @return zwraca prawde gdy zalogowano i false w przecinwym wypadku
   */
  function auth_log_in($login, $password) {
    if (!preg_match("/^[A-Za-z0-9_]{3,19}$/", $login)) {
      echo "preg_match";
      return false;
    }
    $password = hash("sha512", $password);
    $res = db_query("SELECT user_id FROM users WHERE login = '$login' AND pass = '$password'");
    if (count($res) == 1) {
      $_SESSION['user_id'] = $res[0]['user_id'];
      return true;
    }
    echo "didn't find user";
    return false;
  }
  
  /**
   * @brief Wylogowywuje użytkownika
   *
   * (loguje go jako gościa)
   */
  function auth_log_out() {
    $_SESSION['user_id'] = db_query("SELECT user_id FROM users WHERE login = 'guest'")[0]['user_id'];
  }
  
  /**
   * @brief Zwraca informacje o zalogowanym użytkowniku
   *
   * @return tablica asociacyjna z polami: user_id, login, name
   */
  function auth_who() {
    return db_query("SELECT user_id, name, login FROM users WHERE user_id = '{$_SESSION['user_id']}'")[0];
  }
?>
