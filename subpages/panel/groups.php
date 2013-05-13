<?php
  $groups_name = "Grupy";
  $groups_name_permissions = array("EditUsers");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    
    function groups_view($params) {
      if (panel_want_name($params)) return "Grupy";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      $table = new HTMLTag("table", array("width" => "100%"), new HTMLFromString('
          <tr><th>Id</th><th>Nazwa</th><th colspan="2">Akcje</th></tr>
      '));
      
      $groups = db_query("SELECT group_id, name FROM groups");
      
      foreach ($groups as $group) {
        $tr = new HTMLTag("tr");
        foreach ($group as $elem) {
          $tr->add(new HTMLTag("td", array(), strval($elem)));
        }
        $tr->add(new HTMLTag("td", array(), new HTMLTag('a', array("href" => "panel/groups/del/{$group['group_id']}"), "usuń")));
        $tr->add(new HTMLTag("td", array(), new HTMLTag('a', array("href" => "panel/groups/edit/{$group['group_id']}"), "edytuj")));
        $table->add($tr);
      }
      
      return $table;
    }
    
    function groups_add($params) {
      if (panel_want_name($params)) return "Dodaj grupę";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
    }
    
    function groups_edit($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM groups WHERE group_id = '" . intval($params[0]) . "'"))) {
        $group_id = intval($params[0]);
      } else {
        return groups_view(array());
      }
  
    }
    
    function groups_del($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM groups WHERE group_id = '" . intval($params[0]) . "'"))) {
        $group_id = intval($params[0]);
      } else {
        return groups_view(array());
      }
      
      if ($form = form_load("panel_groups_del")) {
        if ($form['del'] == "Tak") {
          db_connect();
          $u = db_query("SELECT group_id FROM groups WHERE name = 'Administrators' OR name = 'Guests'");
          if ($group_id != $u[0]['group_id'] && $group_id != $u[1]['group_id']) {
            db_query("DELETE FROM groups WHERE group_id = '{$group_id}'");
          } else {
            return new HTMLFromString("<h3>Nie można usunąć grupy Administrators lub Guests.</h3>");
          }
          db_close();
          return new HTMLFromString("<h3>Usunięto grupę.</h3>");
        } else {
          return users_view(array());
        }
      } else {
        $group = db_query("SELECT name FROM groups WHERE group_id = '{$group_id}'");
        return form_create("panel_groups_del", "panel/groups/del/{$group_id}", array("del" => "/./"), new HTMLFromString('
          Czy na pewno chcesz usunąć grupę <b>' . $group[0]['name'] . '</b>?<br />
          <input type="submit" name="del" value="Tak" />
          <input type="submit" name="del" value="Nie" />
        '));
      }
    }
    
    function groups_main($params) {
      return groups_view($params);
    }
  }
?>
