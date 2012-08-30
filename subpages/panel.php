<?php
  function main($params) {
    global $html;
    
    if ($form = form_load("panel")) {
      auth_log_in($form['login'], $form['password']);
    }
    
    if (isset($params[0]) && $params[0] == "logout") {
      auth_log_out();
    }
    
    $user = auth_who();
    if ($user['login'] != "guest") {
      $html->select("leftside")->add(new HTMLTag("div", array("class" => "menu"), array(
        new HTMLTag("h1", array(), "Panel"),
        "Jesteś zalogowany jako:",
        new HTMLTag("br"),
        new HTMLTag("b", array(), $user['name']),
        new HTMLTag("br"),
        new HTMLTag("a", array("href" => "panel/logout"), "wyloguj"),
        new HTMLTag("div", array("class" => "menufooter"))
      )), false);
      return new HTMLFromString("<h2>Witaj w panelu.</h2>");
    } else {
      return form_create("panel", array("login" => "/./", "password" => "/./"), new HTMLFromString('
        Login <input type="text" name="login" /><br />
        Hasło <input type="text" name="password" /><br />
        <input type="submit" value="zaloguj" />
      '));
    }
  }
?>
