<?php
  function panel_load_subpanel($subpanel, $params) {
    $funcs = get_defined_functions();
    $users_func = array();
    foreach ($funcs['user'] as $func) {
      if (preg_match("/^{$subpanel}_.*$/", $func)) {
        array_push($users_func, substr($func, strlen($subpanel) + 1));
      }
    }
    
    $panel_page = new HTMLContainer(new HTMLTag("div", array("id" => "subpanel_menu", "style" => "border: 1px solid black;"), array()), "panel_container");
    
    foreach ($users_func as $func) {
      if ($name = call_user_func("{$subpanel}_{$func}", "name")) {
        $panel_page->select("subpanel_menu")->add(new HTMLTag("a", array("href" => "panel/$subpanel/$func", "style" => "margin-right: 10px"), $name));
      }
    }
    
    if (empty($params) || !function_exists("{$subpanel}_{$params[0]}")) {
      $panel_page->add(new HTMLFromString("<h1>{$subpanel} panel</h1>"));
    } else {
      $subsubpanel = array_shift($params);
      $panel_page->add(call_user_func("{$subpanel}_{$subsubpanel}", $params));
    }
    
    return $panel_page;
  }
  
  function panel_want_name($params) {
    return !empty($params) && is_string($params) && $params = "name";
  }
  
  function panel_no_acces_info() {
    return new HTMLFromString("<h1>Nie masz uprawnień do ogladania tej strony.</h1>");
  }

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
      $html->select("leftside")->add(new HTMLFromString('
        <div class="menu">
          <h1>Panel</h1>
          Zalogowany jako:<br />
          <b>' . $user['name'] . '</b><br />
          <ul id="panel_menu">
            <li><a href="panel/logout">Wyloguj</a></li>
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
              $html->select("panel_menu")->add(new HTMLTag("li", array(), array(
                new HTMLTag("a", array("href" => "panel/{$file_name}"), ${"{$file_name}_name"})
              )));
            }
          }
        }
        closedir($dir_handle);
      }
      
      unset($GLOBALS['panel_subpanels_scan']);
      
      if (isset($params[0]) && file_exists("./subpages/panel/{$params[0]}.php")) {
        $subpanel = array_shift($params);
        require("./subpages/panel/{$subpanel}.php");
        return panel_load_subpanel($subpanel, $params);
      } else {
        return new HTMLFromString("<h1>Panel</h1>");
      }
    } else {
      return form_create("panel", array("login" => "/./", "password" => "/./"), new HTMLFromString('
        Login <input type="text" name="login" /><br />
        Hasło <input type="text" name="password" /><br />
        <input type="submit" value="zaloguj" />
      '));
    }
  }
?>
