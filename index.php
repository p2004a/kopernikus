<?php
  /**
   * @brief Strona główna
   * 
   * Użytkowa część systemu wywoływane, odpowiedzialna za wywołanie odpowiednich
   * funkcji.
   * @author Marek "p2004a" Rusinowski
   * @file index.php
   */
   
  require("core.php");

  $html->loadCSS("css/style.css")->addHead(new HTMLTag("title", array(), "beta.kopernik.mielec.pl"));
  $html->addBody(new HTMLFromFile("templates/main_body.html"));
  
  // Load menu
  $menu_file = file("menu.inc");
  array_push($menu_file, "koniec");
  $top_menu = true;
  $name = "";
  $ul = new HTMLTag("ul");
  foreach($menu_file as $menu_file_line) {
    if (trim($menu_file_line) == "") continue;
    $elem = explode("|", $menu_file_line);
    if (count($elem) >= 2) {
      $ul->add(new HTMLTag("li", array(), array(
        new HTMLTag("a", array("href" => trim($elem[1]), "target" => (count($elem) >= 3 ? trim($elem[2]) : "_self")), trim($elem[0]))
      )));
    } else {
      if ($top_menu) {
        $html->select("main")->add($ul);
      } else {
        $html->select("leftside")->add(new HTMLTag("div", array("class" => "menu"), array(
          new HTMLTag("h1", array(), trim($name)),
          $ul,
          new HTMLTag("div", array("class" => "menufooter"))
        )));
      }
      $top_menu = false;
      $ul = new HTMLTag("ul");
      $name = $elem[0];
    }
  }
  
  // Load page content
  if (count($core_params) == 0) {
    array_push($core_params, "main");
  }
  if (!file_exists("./subpages/" . $core_params[0] . ".php")) {
    $subpage = "no_page";
  } else {
    $subpage = $core_params[0];
  }
  array_shift($core_params);
  require("./subpages/" . $subpage . ".php");
  if (!function_exists("{$subpage}_main")) {
    core_error("Found subpage but cannot find {subpage}_main function.");
  }
  $html->select("content")->add(call_user_func("{$subpage}_main", $core_params));
  
  // Load panel box
  if (file_exists("./subpages/panel.php")) {
    require_once("./subpages/panel.php");
    panel_panel_box();
  }
  
  core_render();
?>
