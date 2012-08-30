<?php
  $news_name = "Aktualności";
  $news_name_permissions = array();
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
  
    function news_view($params) {
      if (panel_want_name($params)) return "Przeglądaj aktualności";

      return "Przeglądaj aktualności";
    }
    
    function news_add($params) {
      if (panel_want_name($params)) return "Dodaj nowa";
      
      return "Dodaj nowa aktualność";
    }
    
    function news_del($params) {
      if (panel_want_name($params)) return false;
      
      return "Usuń aktualność";
    }
  
    function news_edit($params) {
      if (panel_want_name($params)) return false;
      
      return "Edytuj aktualność";
    }
  }
?>
