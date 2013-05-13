<?php
  $editme_name = "Ustawienia konta";
  $editme_name_permissions = array();
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    
    function editme_main($params) {
      if (panel_want_name($params)) return false;
      
      db_connect();
      
      $user = auth_who();
      $user = db_query("SELECT * FROM users WHERE user_id = " . $user['user_id']);
      $user = $user[0];
      
      $form = form_load("panel_editme");
      
      if ($form && $form['new_password'] == $form['new_password_check'] && hash("sha512", $form['password']) == $user['pass']) {
        if ($form['new_password'] == "old_password") {
          $pass = $user['pass'];
        } else {
          $pass = hash("sha512", $form['new_password']);
        }
        if ($user['name'] == 'Administrator') $form['name'] = 'Administrator';
        db_query("UPDATE users SET pass = '$pass', name = '{$form['name']}', email = '{$form['email']}', fbid = '{$form['fbid']}' WHERE user_id = {$user['user_id']}");
        
        db_close();
        
        return new HTMLFromString("<h3>Zapisano zmiany</h3>");
      } else {
        db_close();
        
        return form_create("panel_editme", "panel/editme", array(
          "password" => "/./",
          "new_password" => "/./",
          "new_password_check" => "/./",
          "name" => "/^[\.-_a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]{6,39}(?<!Administrator)$/",
          "email" => "/^[A-Za-z0-9\.@_-]{6,39}|$/",
          "fbid" => "/^[0-9]{14,20}|$/"
        ), new HTMLContainer(array(new HTMLFromString('
          Obecne hasło <input type="password" name="password" /><br /><br />
          Nazwa <input type="text" name="name" value="' . $user['name'] . '" /><br />
          Nowe hasło <input type="password" name="new_password" value="old_password" /><br />
          Nowe hasło <input type="password" name="new_password_check" value="old_password" /><br />
          Email <input type="text" name="email" value="' . $user['email'] . '" /><br />
          Facebook ID <input type="text" name="fbid" value="' . $user['fbid'] . '" /><br />
          <br /><input type="submit" />
        '))));
      }
    }
  }
?>
