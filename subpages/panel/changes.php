<?php
  $changes_name = "Zmiany";
  $changes_name_permissions = array("EditChanges");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    
    function changes_main($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditChanges")) return panel_no_acces_info();
      
      $form = form_load("panel_changes");
      
      print_r($form);
      
      if ($form && isset($_FILES['file'])) {
      
        $files_path = "changes";
        $file_name = "no_file";
        
        $dir = opendir($files_path);
        while($file = readdir($dir)){
          if(($file != '.') && ($file != '..') && ($file != '.htaccess' && !is_dir($files_path . '/' . $file))){
            $file_name = $file;
          }
        }
        closedir($dir);
        
        unlink($files_path . "/" . $file_name);
        
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        if(is_uploaded_file($file_tmp)){
          move_uploaded_file($file_tmp, $files_path . "/" . $file_name);
        }
        
        return "Zapisano zmiany.";
      } else {
        return form_create("panel_changes", "panel/changes", array(
          "MAX_FILE_SIZE" => "/10000000/"
        ), '
          <input type="hidden" name="MAX_FILE_SIZE" value="10000000" /> 
          Plik: <input type="file" name="file" /><br />
          <input type="submit" value="wyślij" class="button" />
        ')->setAttribute("enctype", "multipart/form-data");
      }
    }
  }
?>