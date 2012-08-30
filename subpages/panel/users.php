<?php
  $users_name = "Użytkownicy";
  $users_name_permissions = array("EditUsers");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
  
    function users_add_user($params) {
      if (panel_want_name($params)) return "Dodaj Użytkownika";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();

      return new HTMLFromString("<h1>Dodaj użytkownika</h1>");
    }
    
    function users_del_user($params) {
      if (panel_want_name($params)) return "Usuń Użytkownika";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      return new HTMLFromString("<h1>Usuń użytkownika</h1>");
    }
  
  }
?>
