<?php
  $users_name = "Użytkownicy";
  $users_name_permissions = array("EditUsers");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    
    function users_view($params) {
      if (panel_want_name($params)) return "Użytkownicy";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      $table = new HTMLTag("table", array("width" => "100%"), array(new HTMLFromString('
          <tr><th>Id</th><th>Nazwa</th><th>Login</th><th>Grupa</th><th>e-mail</th></tr>
      ')));
      
      $users = db_query("SELECT user_id, name, login, group_id, email FROM users");
      
      foreach ($users as $user) {
        $tr = new HTMLTag("tr");
        foreach ($user as $elem) {
          $tr->add(new HTMLTag("td", array(), strval($elem)));
        }
        $table->add($tr);
      }
      
      return $table;
    }
    
    function users_add($params) {
      if (panel_want_name($params)) return "Dodaj Użytkownika";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();

      return new HTMLFromString("<h1>Dodaj użytkownika</h1>");
    }
    
    function users_del($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      return new HTMLFromString("<h1>Usuń użytkownika</h1>");
    }
    
    function users_main($params) {
      return users_view($params);
    }
  }
?>
