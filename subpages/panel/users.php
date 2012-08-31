<?php
  $users_name = "Użytkownicy";
  $users_name_permissions = array("EditUsers");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    
    function users_view($params) {
      if (panel_want_name($params)) return "Użytkownicy";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      $table = new HTMLTag("table", array("width" => "100%"), new HTMLFromString('
          <tr><th>Id</th><th>Nazwa</th><th>Login</th><th>Grupa</th><th>e-mail</th><th>Akcje</th></tr>
      '));
      
      $users = db_query("SELECT users.user_id, users.name, users.login, groups.name AS 'group_name', users.email FROM users INNER JOIN groups ON users.group_id = groups.group_id");
      
      foreach ($users as $user) {
        $tr = new HTMLTag("tr");
        foreach ($user as $elem) {
          $tr->add(new HTMLTag("td", array(), strval($elem)));
        }
        $tr->add(new HTMLTag("td", array(), new HTMLTag('a', array("href" => "panel/users/del/{$user['user_id']}"), "usuń")));
        $table->add($tr);
      }
      
      return $table;
    }
    
    function users_add($params) {
      if (panel_want_name($params)) return "Dodaj Użytkownika";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      $form = form_load("panel_user_add");
      if ($form && $form['password'] == $form['password_check']) {
        if (0 == count(db_query("SELECT * FROM users WHERE login = '{$form['login']}'
          UNION SELECT * FROM users WHERE name = '{$form['name']}'
          UNION SELECT * FROM deleted_users WHERE login = '{$form['login']}'
         "))) {
          $pass = hash("sha512", $form['password']);
          db_query("INSERT INTO users (login, group_id, pass, name, email) VALUES ('{$form['login']}', '{$form['group']}', '{$pass}', '{$form['name']}', '{$form['email']}')");
          return new HTMLFromString("<h3>Stworzono użytkownika</h3>");
        } else {
          return new HTMLFromString("<h3>Istnieje lub już kiedyś istniał użytkownik o rządanym loginie lub nazwie</h3>");
        }
      } else {
        $groups = db_query("SELECT * FROM groups");
        
        $select = new HTMLTag("select", array("name" => "group"));
        foreach ($groups as $group) {
          $select->add(new HTMLTag("option", array("value" => $group['group_id']), $group['name']));
        }
        
        return form_create("panel_user_add", "panel/users/add", array(
          "login" => "/^[A-Za-z0-9_]{3,19}$/",
          "password" => "/./",
          "password_check" => "/./",
          "name" => "/^[\.-_a-zA-ZąęćżźńłóśĄĆĘŁŃÓŚŹŻ\s]{6,39}$/",
          "email" => "/^[A-Za-z0-9\.@_-]{6,39}$/",
          "group" => "/^[0-9]{1,8}$/"
        ), new HTMLContainer(array(new HTMLFromString('
          Login <input type="text" name="login" /><br />
          Hasło <input type="password" name="password" /><br />
          Re-Hasło <input type="password" name="password_check" /><br />
          Nazwa <input type="text" name="name" /><br />
          Email <input type="text" name="email" /><br />
          Grupa 
        '), $select, new HTMLFromString('
          <br /><input type="submit" />
        '))));
      }
    }
    
    function users_del($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM users WHERE user_id = '" . intval($params[0]) . "'"))) {
        $user_id = intval($params[0]);
      } else {
        return users_view(array());
      }
      
      if ($form = form_load("panel_users_del")) {
        if ($form['del'] = "Tak") {
          db_connect();
          $u = db_query("SELECT user_id FROM users WHERE login = 'admin' OR login = 'guest'");
          if ($user_id != $u[0]['user_id'] && $user_id != $u[1]['user_id']) {
            db_query("INSERT INTO deleted_users SELECT * FROM users WHERE user_id = '{$user_id}'");
            db_query("DELETE FROM users WHERE user_id = '{$user_id}'");
          }
          db_close();
          return new HTMLFromString("<h3>Usunięto użytkownika.</h3>");
        } else {
          return users_view(array());
        }
      } else {
        $user = db_query("SELECT name FROM users WHERE user_id = '{$user_id}'");
        return form_create("panel_users_del", "panel/users/del/{$user_id}", array("del" => "/./"), new HTMLFromString('
          Czy na pewno chcesz usunąć użytkownika <b>' . $user[0]['name'] . '</b>?<br />
          <input type="submit" name="del" value="Tak" />
          <input type="submit" name="del" value="Nie" />
        '));
      }
    }
    
    function users_main($params) {
      return users_view($params);
    }
  }
?>
