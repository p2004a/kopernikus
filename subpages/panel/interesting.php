<?php
  $interesting_name = "Zobacz";
  $interesting_name_permissions = array("EditViewInteresting");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    function interesting_main($params) {
      global $html;
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditViewInteresting")) return panel_no_acces_info();
      
      if ($form = form_load("panel_interesting")) {
        echo "<pre>";
        print_r($form);
        echo "</pre>";
        return "<h3>Zapisano zmiany</h3>";
      } else {
      
        $html->loadJS("//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js");
        $html->loadJS("//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js");
        $html->loadJS("js/edit_interesting.js");
       
        $button = new HTMLTag("button", array("type" => "button", "onclick" => "add_elem()"), "dodaj element");
        
        $infos = db_query("SELECT * FROM view_interestig ORDER BY position");
        $ul = new HTMLTag("ul", array("id" => "interesting"));
        foreach ($infos as $info) {
          $li = new HTMLTag("li", array(), array(
            new HTMLTag('input', array("type" => "text", "name" => "title[]", "value" => $info['title'])),
            new HTMLTag('input', array("type" => "text", "name" => "url[]", "value" => $info['url'])),
            new HTMLTag('select', array("name" => "target[]"), array(
              new HTMLTag('option', array("value" => "_target", $info['visible'] == "_target" ? "selected" : "" => "1"), "_target"),
              new HTMLTag('option', array("value" => "_blank", $info['visible'] == "_blank" ? "selected" : "" => "1"), "_blank")
            )),
            new HTMLTag('input', array("type" => "checkbox", "name" => "visible[]", "value" => "1", $info['visible'] == 1 ? "checked" : "" => "1")),
            new HTMLTag('img', array("src" => "img/clock.png", "alt" => "drag icon"))
          ));
          $ul->add($li);
        }
        
        $form = form_create("panel_interesting", "panel/interesting", array(), array(
          $ul,
          new HTMLTag("input", array("type" => "submit", "value" => "zapisz"))
        ))->setAttribute("id", "form_interesting");
        
        return new HTMLContainer(array(
          $button,
          $form
        ));
      
      }
    }
  }
?>
