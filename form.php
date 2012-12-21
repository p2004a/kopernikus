<?php
  /**
   * @brief Funkcie obsługi formularzy
   * 
   * Funkcje i klasy potrzebne do tworzenia bezpiecznych formularzy
   * Prefiks elementów w pliku: form
   * @author Marek "p2004a" Rusinowski
   * @file form.php
   */
   
   /**
    * @brief Tworzy formularz
    *
    * @param $form_name nazwa formularza
    * @param $action to samo co action w <form>
    * @param $fields tablica asocjacyjna nazwa => wyrazanie_regularne opisujace pola formularza
    * @param $code kod formularza, string albo HTMLObject
    * @return kod rozmularza
    */
  function form_create($form_name, $action, $fields, $code) {
    $csrf_field = uniqid();
    $fields['csrf_field'] = "/$csrf_field/";
    $_SESSION['forms'][$form_name] = $fields;
    return new HTMLTag("form", array("action" => $action, "method" => "POST"), array(
      new HTMLTag("input", array("type" => "hidden", "value" => $csrf_field, "name" => "csrf_field")),
      $code
    ));
  }
  
   /**
    * @brief Ładuje formularz
    *
    * @param $form_name nazwa formularza
    * @return fałsz jeśli błedy w przesłanym formularzu albo tablica asocjacyjna z formularzem
    */
  function form_load($form_name) {
    if (!isset($_SESSION['forms'][$form_name])) {
      return false;
    }
    foreach ($_SESSION['forms'][$form_name] as $name => $value) {
      if (!array_key_exists($name, $_POST)
       || !is_string($_POST[$name])
       || !preg_match($value, $_POST[$name])) {
        unset($_SESSION['forms'][$form_name]);
        return false;
      }
    }
    unset($_SESSION['forms'][$form_name]);
    return $_POST;
  }
?>
