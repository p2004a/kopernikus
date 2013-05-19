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
    
    function _groups_gen_form() {
      $privileges = db_query("SELECT * FROM privileges ORDER BY name");
      
      $regexps = array("name" => "/^[A-Za-z0-9_]{3,50}(?<!Administrators)(?<!Guests)$/");
      
      $form_body = new HTMLContainer(array(
        "Nazwa: ",
        new HTMLTag("input", array("type" => "text", "name" => "name")),
        new HTMLTag("br"),
        new HTMLTag("br")
      ));
      
      foreach ($privileges as $privilage) {
        $regexps[$privilage['name']] = "/^0|1$/";
        $form_body->add(new HTMLTag("div", array(), array(
          new HTMLTag("input", array("type" => "hidden", "name" => $privilage['name'], "value" => "0")),
          new HTMLTag("input", array("type" => "checkbox", "name" => $privilage['name'], "value" => "1", "id" => 'Id' . $privilage['name'])),
          new HTMLTag("b", array(), $privilage['name']),
          new HTMLTag("p", array(), $privilage['description'])
        )));
      }
      
      $form_body->add(new HTMLTag("input", array("type" => "submit")));
      
      return array("regexps" => $regexps, "form_body" => $form_body);
    }
    
    function groups_add($params) {
      if (panel_want_name($params)) return "Dodaj grupę";
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      if ($form = form_load("panel_groups_add")) {
        if (0 == count(db_query("SELECT * FROM groups WHERE name = '{$form['name']}'"))) {
          db_connect();
          db_query("INSERT INTO groups (name) VALUES ('{$form['name']}')");
          $group = db_query("SELECT * FROM groups WHERE name = '{$form['name']}'");
          $group_id = $group[0]['group_id'];
          $privileges = db_query("SELECT * FROM privileges");
          foreach ($privileges as $privilage) {
            if ($form[$privilage['name']] == '1') {
              db_query("INSERT INTO group_permissions (group_id, privilege_id) VALUES ('{$group_id}', '{$privilage['privilege_id']}')");
            }
          }
          db_close();
          return new HTMLFromString("<h3>Stworzono grupę</h3>");
        } else {
          return new HTMLFromString("<h3>Istnieje grupa o wybranej nazwie</h3>");
        }
      } else {
        $form = _groups_gen_form();
        return form_create("panel_groups_add", "panel/groups/add", $form['regexps'], $form['form_body']);
      }
    }
    
    function groups_edit($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditUsers")) return panel_no_acces_info();
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM groups WHERE group_id = '" . intval($params[0]) . "'"))) {
        $group_id = intval($params[0]);
      } else {
        return groups_view(array());
      }
  
      if ($form = form_load("panel_groups_edit")) {
        db_connect();
        $u = db_query("SELECT group_id, name FROM groups WHERE name = 'Administrators' OR name = 'Guests'");
        if ($group_id == $u[0]['group_id'] || $group_id == $u[1]['group_id']) {
          return new HTMLFromString("<h3>Nie można zedytować grupy Administrators lub Guests.</h3>");
        }
        db_query("UPDATE groups SET name = '{$form['name']}' WHERE group_id = '{$group_id}'");
        db_query("DELETE FROM group_permissions WHERE group_id = '{$group_id}'");
        $privileges = db_query("SELECT * FROM privileges");
        foreach ($privileges as $privilage) {
          if ($form[$privilage['name']] == '1') {
            db_query("INSERT INTO group_permissions (group_id, privilege_id) VALUES ('{$group_id}', '{$privilage['privilege_id']}')");
          }
        }
        db_close();
        return new HTMLFromString("<h3>Zedytowano grupę</h3>");
      } else {
        $form = _groups_gen_form();
        
        $group = db_query("SELECT * FROM groups WHERE group_id = '{$group_id}'");
        
        $form['form_body']->select('name.name')->setAttribute("value", $group[0]['name']);
        
        $group_permissions = db_query("SELECT privileges.name FROM group_permissions INNER JOIN privileges ON privileges.privilege_id = group_permissions.privilege_id WHERE group_id = {$group_id}");
        
        foreach ($group_permissions as $permission) {
          $form['form_body']->select("Id{$permission['name']}")->setAttribute("checked", "checked");
        }
        
        return form_create("panel_groups_edit", "panel/groups/edit/{$group_id}", $form['regexps'], $form['form_body']);
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
          $u = db_query("SELECT group_id, name FROM groups WHERE name = 'Administrators' OR name = 'Guests' ORDER BY name");
          if ($group_id != $u[0]['group_id'] && $group_id != $u[1]['group_id']) {
            db_query("DELETE FROM groups WHERE group_id = '{$group_id}'");
            db_query("DELETE FROM group_permissions WHERE group_id = '{$group_id}'");
            db_query("UPDATE users SET group_id = '{$u[1]['group_id']}' WHERE group_id = '{$group_id}'");
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
