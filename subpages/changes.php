<?php
  function changes_main() {
    $files_path = "changes";
    $file_name = "no_file";
    
    $dir = opendir($files_path);
    while($file = readdir($dir)){
      if(($file != '.') && ($file != '..') && ($file != '.htaccess' && !is_dir($files_path . '/' . $file))){
        $file_name = $file;
      }
    }
    closedir($dir);
    
    if ($file_name == "no_file") exit;
    
    $path = $files_path . '/' . $file_name;
    if (!file_exists($path)) exit;
    $file = fopen($path,'r');
    $size = filesize($path);
    $content = fread($file, $size);
    fclose($file);

    header("Content-Type: " . mime_content_type($path));
    header("Content-Length: $size;");
    header("Content-Disposition: inline; filename=" . $file_name);

    echo $content;
    exit;
  }
?>
