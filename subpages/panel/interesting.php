<?php
  $interesting_name = "Zobacz";
  $interesting_name_permissions = array("EditViewInteresting");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    function interesting_main($params) {
      global $html;
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditViewInteresting")) return panel_no_acces_info();
      
      if ($form = form_load("panel_interesting")) {
        db_connect();
        db_query("DELETE FROM view_interestig");
        for ($i = 0; $i < count($form['visible']); ++$i) {
          db_query("INSERT INTO view_interestig (url, title, target, position, visible) VALUES ('" . db_esc_str($form['url'][$i]) . "', '" . db_esc_str($form['title'][$i]) . "', '" . db_esc_str($form['target'][$i]) . "', '$i', '" . db_esc_str($form['visible'][$i]) . "')");
        }
        db_close();
        return "<h3>Zapisano zmiany</h3>";
      } else {
      
        $html->loadJS("js/edit_interesting.js");
       
        $button = new HTMLTag("button", array("type" => "button", "onclick" => "add_elem()"), "dodaj element");
        
        $infos = db_query("SELECT * FROM view_interestig ORDER BY position");
        $ul = new HTMLTag("ul", array("id" => "interesting", "style" => "margin-top: 20px;"));
        foreach ($infos as $info) {
          $li = new HTMLTag("li", array(), array(
            new HTMLTag('input', array("type" => "text", "name" => "title[]", "value" => htmlspecialchars($info['title']))),
            new HTMLTag('input', array("type" => "text", "name" => "url[]", "value" => htmlspecialchars($info['url']))),
            new HTMLTag('select', array("name" => "target[]"), array(
              new HTMLTag('option', array("value" => "_target", $info['target'] == "_target" ? "selected" : "" => "1"), "_target"),
              new HTMLTag('option', array("value" => "_blank", $info['target'] == "_blank" ? "selected" : "" => "1"), "_blank")
            )),
            new HTMLTag('input', array("type" => "checkbox", "name" => "visible[]", "value" => "1", $info['visible'] == 1 ? "checked" : "" => "1")),
            new HTMLTag('img', array("src" => "img/clock.png", "alt" => "drag icon", "style" => "cursor:move;"))
          ));
          $ul->add($li);
        }
        
        $form = form_create("panel_interesting", "panel/interesting", array(), array(
          $ul,
          new HTMLTag("input", array("type" => "submit", "value" => "zapisz"))
        ))->setAttribute("id", "form_interesting");
        
        return new HTMLContainer(array(
          $button,
          new HTMLTag("div", array("id" => "delete_row", "style" => "display: inline-block; background-color: #FFCCCC; width: 520px; margin-left:20px; text-align: center; padding: 5px;"), "usuń"),
          $form
        ));
      
      }
    }
  }
?>
