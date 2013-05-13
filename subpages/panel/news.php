<?php
  $news_name = "Aktualności";
  $news_name_permissions = array("EditNews");
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
  
    function news_view($params) {
      if (panel_want_name($params)) return "Przeglądaj aktualności";
      if(!auth_check_permission("EditNews")) return panel_no_acces_info();
      
      $table = new HTMLTag("table", array("width" => "100%"), new HTMLFromString('
          <tr><th>Id</th><th>Autor</th><th>Data</th><th>Tytuł</th><th colspan="2">Akcje</th></tr>
      '));
      
      $newss = db_query("SELECT news.news_id, res.name, news.date, news.title FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id ORDER BY news.date DESC");
      
      foreach ($newss as $news) {
        $tr = new HTMLTag("tr");
        foreach ($news as $elem) {
          $tr->add(new HTMLTag("td", array(), strval($elem)));
        }
        $tr->add(new HTMLTag("td", array(), new HTMLTag('a', array("href" => "panel/news/del/{$news['news_id']}"), "usuń")));
        $tr->add(new HTMLTag("td", array(), new HTMLTag('a', array("href" => "panel/news/edit/{$news['news_id']}"), "edytuj")));
        $table->add($tr);
      }
      
      return $table;

      return "Przeglądaj aktualności";
    }
    
    function _news_get_select_date($t) {
      $select = array();
      $select['day'] = new HTMLTag("select", array("name" => "day"));
      for ($i = 1; $i <= 31; ++$i) {
        $item = new HTMLTag("option", array("value" => $i), strval($i));
        if (date("j", $t) == strval($i)) {
          $item->setAttribute("selected", "selected");
        }
        $select['day']->add($item);
      }
      $select['month'] = new HTMLTag("select", array("name" => "month"));
      for ($i = 1; $i <= 12; ++$i) {
        $item = new HTMLTag("option", array("value" => $i), strval($i));
        if (date("n", $t) == strval($i)) {
          $item->setAttribute("selected", "selected");
        }
        $select['month']->add($item);
      }
      $select['year'] = new HTMLTag("select", array("name" => "year"));
      for ($i = 2000; $i <= 2030; ++$i) {
        $item = new HTMLTag("option", array("value" => $i), strval($i));
        if (date("Y", $t) == strval($i)) {
          $item->setAttribute("selected", "selected");
        }
        $select['year']->add($item);
      }
      return $select;
    }
    
    function _news_del_javascript($str) {
      $r = array("/onclick/i", "/ondblclick/i", "/onmousedown/i", "/onmousemove/i", "/onmouseover/i", "/onmouseout/i", "/onmouseup/i", "/onkeydown/i", "/onkeypress/i", "/onkeyup/i", "/onabort/i", "/onerror/i", "/onload/i", "/onresize/i", "/onscroll/i", "/onunload/i", "/onblur/i", "/onchange/i", "/onfocus/i", "/onreset/i", "/onselect/i", "/onsubmit/i", "/script/i", "/iframe/i", "/data:/i", "/javascript/i");
      return preg_replace($r, '', $str);
    }
    
    function news_add($params) {
      if (panel_want_name($params)) return "Dodaj nowa";
      if(!auth_check_permission("EditNews")) return panel_no_acces_info();
      
      if ($form = form_load("panel_news_add")) {
        $user = auth_who();
        if (!isset($form['data'])) $form['data'] = "";
        db_query("INSERT INTO news (user_id, date, title, text, short_text) VALUES ({$user['user_id']}, '" . sprintf("%04d-%02d-%02d", $form['year'], $form['month'], $form['day']) . "', '" . db_esc_str(htmlspecialchars($form['title'])) . "', '" . db_esc_str(_news_del_javascript($form['data'])) . "', '" . db_esc_str(_news_del_javascript($form['data_short'])) . "')");
        return new HTMLFromString("<h3>Dodano newsa.</h3>");
      } else {
        
        $select = _news_get_select_date(time());
        
        return form_create("panel_news_add", "panel/news/add", array(
          "title" => "/./",
          "data_short" => "/./",
          "day" => "/^[0-9]{1,2}$/",
          "month" => "/^[0-9]{1,2}$/",
          "year" => "/^[0-9]{4,4}$/"
        ), new HTMLContainer(array(
          "Rok ", $select['year'],
          "Miesiąc ", $select['month'],
          "Dzień ", $select['day'],
          new HTMLFromString('<br />Tytuł <input type="text" style="width:500px;" name="title" /><br />'),
          'Skrócona treść<br />', new HTMLCKEditor("data_short", "News"),
          'Pełna treść<br />', new HTMLCKEditor("data", "News"),
          new HTMLFromString('
            <br />Obrazek <input type="file" name="image" size="40" /><br />
            <input type="submit" />
          ')
        )));
      }
      
      return "Dodaj nowa aktualność";
    }
    
    function news_edit($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditNews")) return panel_no_acces_info();
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM news WHERE news_id = '" . intval($params[0]) . "'"))) {
        $news_id = intval($params[0]);
      } else {
        return news_view(array());
      }
      
      $user = auth_who();
      $news = db_query("SELECT * FROM news WHERE news_id = '{$news_id}'");
      $news = $news[0];
      
      if ($news['user_id'] != $user['user_id'] && !auth_check_permission("EditNewsAll")) return panel_no_acces_info();
      
      if ($form = form_load("panel_news_edit")) {
        if (!isset($form['data'])) $form['data'] = "";
        db_query("UPDATE news SET user_id = {$user['user_id']}, date = '" . sprintf("%04d-%02d-%02d", $form['year'], $form['month'], $form['day']) . "', title = '" . db_esc_str(htmlspecialchars($form['title'])) . "', text = '" . db_esc_str(_news_del_javascript($form['data'])) . "', short_text = '" . db_esc_str(_news_del_javascript($form['data_short'])) . "' WHERE news_id = '{$news_id}'");
        return new HTMLFromString("<h3>Zedytowano newsa.</h3>");
        
      } else {
      
        $select = _news_get_select_date(strtotime($news['date']));
        
        return form_create("panel_news_edit", "panel/news/edit/{$news_id}", array(
          "title" => "/./",
          "data_short" => "/./",
          "day" => "/^[0-9]{1,2}$/",
          "month" => "/^[0-9]{1,2}$/",
          "year" => "/^[0-9]{4,4}$/"
        ), new HTMLContainer(array(
          "Rok ", $select['year'],
          "Miesiąc ", $select['month'],
          "Dzień ", $select['day'],
          new HTMLFromString('<br />Tytuł <input type="text" style="width:500px;" name="title" value="' . $news['title'] . '" /><br />'),
          'Skrócona treść<br />', new HTMLCKEditor("data_short", "News", $news['short_text']),
          'Pełna treść<br />', new HTMLCKEditor("data", "News", $news['text']),
          new HTMLFromString('
            <br />Obrazek <input type="file" name="image" size="40" /><br />
            <input type="submit" />
          ')
        )));
      }
      return "Edytuj aktualność";
    }
    
    function news_del($params) {
      if (panel_want_name($params)) return false;
      if(!auth_check_permission("EditNews")) return panel_no_acces_info();
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM news WHERE news_id = '" . intval($params[0]) . "'"))) {
        $news_id = intval($params[0]);
      } else {
        return news_view(array());
      }
      
      $user = auth_who();
      $news = db_query("SELECT * FROM news WHERE news_id = '{$news_id}'");
      $news = $news[0];
      
      if ($news['user_id'] != $user['user_id'] && !auth_check_permission("EditNewsAll")) return panel_no_acces_info();
      
      if ($form = form_load("panel_news_del")) {
        if ($form['del'] == "Tak") {
          db_query("DELETE FROM news WHERE news_id = '{$news_id}'");
          return new HTMLFromString("<h3>Usunięto news.</h3>");
        } else {
          return news_view(array());
        }
      } else {
        $news = db_query("SELECT title FROM news WHERE news_id = '{$news_id}'");
        return form_create("panel_news_del", "panel/news/del/{$news_id}", array("del" => "/./"), new HTMLFromString('
          Czy na pewno chcesz usunąć news <b>' . $news[0]['title'] . '</b>?<br />
          <input type="submit" name="del" value="Tak" />
          <input type="submit" name="del" value="Nie" />
        '));
      }
    }
    
    function news_main($params) {
      return news_view($params);
    }
  }
?>
