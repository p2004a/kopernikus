<?php
  $logs_name = "Logi";
  $logs_name_permissions = array("ViewLogs");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
    
    function logs_main($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("ViewLogs")) return panel_no_acces_info();
      
      $logs_per_page = 40;
      
      if (!empty($params) && is_numeric($params[0]) && intval($params[0]) > 0) {
        $page = intval($params[0]);
      } else {
        $page = 0;
      }
      
      $logs_num = db_query("SELECT COUNT(*) FROM logs");
      $logs_num = $logs_num[0]['COUNT(*)'];
      
      $pagination = new HTMLTag("div", array("style" => "text-align: center; width: 100%;"));
      for ($i = 0; $i * $logs_per_page < $logs_num; ++$i) {
        $pagination->add(array(
          new HTMLTag("a", array("href" => "panel/logs/$i"), $i == $page ? new HTMLTag("b", array(), strval($i + 1)) : strval($i + 1)),
          ' '
        ));
      }
      
      $table = new HTMLTag("table", array("width" => "100%"), new HTMLFromString('
          <tr><th width="20">Id</th><th width="140">Data</th><th width="110">Autor</th><th>Wiadomość</th></tr>
      '));
      
      $logs = db_query("SELECT logs.log_id, logs.time, logs.user_id, logs.message, users.login FROM logs INNER JOIN users ON users.user_id = logs.user_id ORDER BY logs.time DESC LIMIT {$logs_per_page} OFFSET " . $logs_per_page * $page);
      
      foreach ($logs as $log) {
        $table->add(new HTMLTag("tr", array(), array(
          new HTMLTag("td", array(), $log['log_id']),
          new HTMLTag("td", array(), $log['time']),
          new HTMLTag("td", array(), $log['login'] . '(' . $log['user_id'] . ')'),
          new HTMLTag("td", array(), $log['message'])
        )));
      }
      
      return new HTMLContainer(array(
        $pagination,
        $table,
        $pagination
      ));
    }
  }
?>
