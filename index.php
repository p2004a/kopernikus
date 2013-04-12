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

  $html->loadCSS("css/style.css")
       ->addHead(new HTMLTag("title", array(), "II Liceum Ogólnokształcące im. Mikołaja Kopernika w Mielcu"))
       ->addHead(new HTMLTag("link", array("rel" => "shortcut icon", "href" => "img/favicon.ico")));

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
      $ul->add(new HTMLTag("a", array("href" => trim($elem[1]), "target" => (count($elem) >= 3 ? trim($elem[2]) : "_self")), array(
        new HTMLTag("li", array(), trim($elem[0]))
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
    $subpage_content = db_query("SELECT * FROM subpages WHERE name = '{$core_params[0]}'");
    if (count($subpage_content) > 0) {
      $out = new HTMLFromFile("templates/subpage.html");
      $out->select(".title")->add($subpage_content[0]['title']);
      $out->select(".text")->add(new HTMLFromString($subpage_content[0]['text']));
      $html->select("content")->add($out);
      $subpage_file = false;
    } else {
      $subpage_file = "no_page";
    }
  } else {
    $subpage_file = $core_params[0];
  }
  if ($subpage_file) {
    array_shift($core_params);
    require("./subpages/" . $subpage_file . ".php");
    if (!function_exists("{$subpage_file}_main")) {
      core_error("Found subpage but cannot find {subpage}_main function.");
    }
    $html->select("content")->add(call_user_func("{$subpage_file}_main", $core_params));
  }
  
  // Load leftboxes
  $leftboxes = db_query("SELECT * FROM subpages WHERE name LIKE 'leftbox%' ORDER BY name");
  foreach ($leftboxes as &$box) {
    $html->select("leftside")->add(
      new HTMLTag("div", array("class" => "menu"), array(
        new HTMLTag("h1", array(), $box['title']),
        $box['text'],
        new HTMLTag("div", array("class" => "menufooter"))
      )
    ));
  }
  
  // Load topboxes
  $leftboxes = db_query("SELECT * FROM subpages WHERE name LIKE 'topbox%' ORDER BY name DESC");
  foreach ($leftboxes as &$box) {
    $html->select("content")->add($box['text'], false);
  }
  
  // Load panel box
  if (file_exists("./subpages/panel.php")) {
    require_once("./subpages/panel.php");
    panel_panel_box();
  }
  
  // Load Google Analytics code if exists
  if (file_exists("js/googleanalytics.js")) {
    $html->loadJS("js/googleanalytics.js");
  }
  
  core_render();
?>
