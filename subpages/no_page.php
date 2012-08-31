<?php
  function no_page_main($params) {
    return new HTMLTag("h2", array("align" => "center"), array(
      "Żądana podstrona nie istnieje."
    ));
  }
?>
