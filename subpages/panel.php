<?php
  function panel_load_subpanel($subpanel, $params) {
    $funcs = get_defined_functions();
    $users_func = array();
    foreach ($funcs['user'] as $func) {
      if (preg_match("/^{$subpanel}_.*$/", $func) && $func != "{$subpanel}_main") {
        array_push($users_func, substr($func, strlen($subpanel) + 1));
      }
    }
    
    $panel_page = new HTMLContainer(new HTMLTag("div", array("id" => "subpanel_menu"), array()), "panel_container");
    
    foreach ($users_func as $func) {
      if ($name = call_user_func("{$subpanel}_{$func}", "name")) {
        $panel_page->select("subpanel_menu")->add(new HTMLTag("a", array("href" => "panel/$subpanel/$func"), $name));
      }
    }
    
    if (empty($params) || !function_exists("{$subpanel}_{$params[0]}")) {
      if (!function_exists("{$subpanel}_main")) {
        $panel_page->add(new HTMLFromString("<h1>{$subpanel} panel</h1>"));
        return $panel_page;
      }
      $params = array("main");
    }
    
    $subsubpanel = array_shift($params);
    $panel_page->add(call_user_func("{$subpanel}_{$subsubpanel}", $params));
    
    return $panel_page;
  }
  
  function panel_want_name($params) {
    return !empty($params) && is_string($params) && $params = "name";
  }
  
  function panel_no_acces_info() {
    return new HTMLFromString("<h2>Nie masz uprawnień do ogladania tej strony.</h2>");
  }
  
  function panel_panel_box() {
    global $html;
    
    static $loaded = false;
    
    $user = auth_who();
    
    if (!$loaded && $user['login'] != "guest") {
      $loaded = true;
      
      $html->select("leftside")->add(new HTMLFromString('
        <div class="menu">
          <h1>Panel</h1>
          <p>
            Zalogowany jako:<br />
            <b>' . $user['name'] . '</b><br />
          </p>
          <ul id="panel_menu">
            <a href="panel/logout"><li>Wyloguj</li></a>
          </ul>
          <div class="menufooter"></div>
        </div>
      '), false);
      
      $GLOBALS['panel_subpanels_scan'] = true;
      
      if ($dir_handle = opendir('subpages/panel/')) {
        while (false !== ($file_name = readdir($dir_handle))) {
          if (preg_match("/^.*\.php$/", $file_name)) {
            include("subpages/panel/$file_name");
            $file_name = substr($file_name, 0, -4);
            if (call_user_func_array("auth_check_permission", ${"{$file_name}_name_permissions"})) {
              $html->select("panel_menu")->add(new HTMLTag("a", array("href" => "panel/{$file_name}"), array(
                new HTMLTag("li", array(), ${"{$file_name}_name"})
              )));
            }
          }
        }
        closedir($dir_handle);
      }
      
      unset($GLOBALS['panel_subpanels_scan']);
    }
  }

  function panel_main($params) {
    if ($form = form_load("panel_login")) {
      auth_log_in($form['login'], $form['password']);
    }
    
    if (isset($params[0]) && $params[0] == "logout") {
      auth_log_out();
    }
    
    $user = auth_who();
    if ($user['login'] != "guest") {
      panel_panel_box();
      
      if (isset($params[0]) && file_exists("./subpages/panel/{$params[0]}.php")) {
        $subpanel = array_shift($params);
        require("./subpages/panel/{$subpanel}.php");
        return panel_load_subpanel($subpanel, $params);
      } else {
        return new HTMLFromString("<h1>Panel</h1>");
      }
    } else {
      return form_create("panel_login", "panel", array("login" => "/./", "password" => "/./"), new HTMLFromString('
        Login <input type="text" name="login" /><br />
        Hasło <input type="password" name="password" /><br />
        <input type="submit" value="zaloguj" />
      '));
    }
  }
?>
