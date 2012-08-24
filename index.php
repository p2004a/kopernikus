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

  $html->loadCSS("css/style.css")->addHead(new HTMLTag("title", array(), array("beta.kopernik.mielec.pl")));
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
    if (count($elem) == 2) {
      $ul->add(new HTMLTag("li", array(), array(
        new HTMLTag("a", array("href" => trim($elem[1])), trim($elem[0]))
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
  
  core_render();
?>
