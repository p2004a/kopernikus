<?php
  $news_name = "Aktualności";
  $news_name_permissions = array();
  if (!isset($GLOBALS['panel_subpanels_scan'])) {
  
    function news_view($params) {
      if (panel_want_name($params)) return "Przeglądaj aktualności";
      
      $table = new HTMLTag("table", array("width" => "100%"), new HTMLFromString('
          <tr><th>Id</th><th>Autor</th><th>Data</th><th>Tytuł</th><th>Akcje</th></tr>
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
    
    function news_add($params) {
      if (panel_want_name($params)) return "Dodaj nowa";
      
      if ($form = form_load("panel_news_add")) {
        $user = auth_who();
        db_query("INSERT INTO news (user_id, date, title, text) VALUES ({$user['user_id']}, '" . sprintf("%04d-%02d-%02d", $form['year'], $form['month'], $form['day']) . "', '" . db_esc_str(htmlspecialchars($form['title'])) . "', '" . db_esc_str($form['data']) . "')");
        return new HTMLFromString("<h3>Dodano newsa.</h3>");
      } else {
        
        $select_day = new HTMLTag("select", array("name" => "day"));
        for ($i = 1; $i <= 31; ++$i) {
          $item = new HTMLTag("option", array("value" => $i), strval($i));
          if (date("j") == strval($i)) {
            $item->setAttribute("selected", "selected");
          }
          $select_day->add($item);
        }
        $select_month = new HTMLTag("select", array("name" => "month"));
        for ($i = 1; $i <= 12; ++$i) {
          $item = new HTMLTag("option", array("value" => $i), strval($i));
          if (date("n") == strval($i)) {
            $item->setAttribute("selected", "selected");
          }
          $select_month->add($item);
        }
        $select_year = new HTMLTag("select", array("name" => "year"));
        for ($i = 2000; $i <= 2030; ++$i) {
          $item = new HTMLTag("option", array("value" => $i), strval($i));
          if (date("Y") == strval($i)) {
            $item->setAttribute("selected", "selected");
          }
          $select_year->add($item);
        }
        
        return form_create("panel_news_add", "panel/news/add", array(
          "title" => "/./",
          "data" => "/./",
          "day" => "/^[0-9]{1,2}$/",
          "month" => "/^[0-9]{1,2}$/",
          "year" => "/^[0-9]{4,4}$/"
        ), new HTMLContainer(array(
          "Rok ", $select_year,
          "Miesiąc ", $select_month,
          "Dzień ", $select_day,
          new HTMLFromString('<br />Tytuł <input type="text" style="width:500px;" name="title" /><br />'),
          new HTMLCKEditor("data", "News"),
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
      
      return "Edytuj aktualność";
    }
    
    function news_del($params) {
      if (panel_want_name($params)) return false;
      
      if (!empty($params) && is_numeric($params[0]) && 1 == count(db_query("SELECT * FROM news WHERE news_id = '" . intval($params[0]) . "'"))) {
        $news_id = intval($params[0]);
      } else {
        return news_view(array());
      }
      
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
