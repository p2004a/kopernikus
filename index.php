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
        new HTMLTag("a", array("href" => trim($elem[1]), "target" => (count($elem) >= 3 ? $elem[2] : "_self")), trim($elem[0]))
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
  if (count($params) == 0) {
    array_push($params, "main");
  }
  if (!file_exists("./subpages/" . $params[0] . ".php")) {
    $subpage = "no_page";
  } else {
    $subpage = $params[0];
  }
  array_splice($params, 0, 1);
  require("./subpages/" . $subpage . ".php");
  if (!function_exists("main")) {
    core_error("Found subpage but cannot find main function.");
  }
  $html->select("content")->add(main($params));
  
  core_render();
?>
