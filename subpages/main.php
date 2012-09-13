<?php
  function main_main($params) {
  
    $months = array(1 => "stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
  
    if (isset($params[0]) && $params[0] == "view") {
      if (isset($params[1]) && is_numeric($params[1]) && 1 == count(db_query("SELECT * FROM news WHERE news_id = '" . intval($params[1]) . "'"))) {
        $news_id = intval($params[1]);
        
        $newss = db_query("SELECT res.name, news.news_id, news.date, news.title, news.text, news.image FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id WHERE news.news_id = $news_id");
        $news = $newss[0];
      
        $news_html = new HTMLFromFile("templates/single_news.html");
        $news_html->select(".singlenews")->setAttribute("style", "border-top: 1px solid #EBEBEB;");
        $news_html->select(".readmore")->hide();
        
        $news_html->select(".title")->add($news['title']);
        $news_html->select(".text")->add($news['text']);
        $t = strtotime($news['date']);
        $news_html->select(".time")->add(sprintf("%s %s %sr. | %s", date("j", $t), $months[intval(date("n", $t))], date("Y", $t), $news['name']));
        return $news_html;
        
      } else {
        
        $table = new HTMLTag("table", array("width" => "100%"), new HTMLFromString('
            <tr><th>Autor</th><th>Data</th><th>Tytuł</th><th>Akcje</th></tr>
        '));
        
        $newss = db_query("SELECT news.news_id, res.name, news.date, news.title FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id ORDER BY news.date DESC");
        
        foreach ($newss as $news) {
          $tr = new HTMLTag("tr");
          foreach ($news as $key => $elem) {
            if ($key == "news_id") continue;
            $tr->add(new HTMLTag("td", array(), strval($elem)));
          }
          $tr->add(new HTMLTag("td", array(), new HTMLTag('a', array("href" => "main/view/{$news['news_id']}"), "zobacz")));
          $table->add($tr);
        }
        
        return $table;
        
      }
    } else {
      $content = new HTMLFromFile("templates/news.html");
      $newss = db_query("SELECT res.name, news.news_id, news.date, news.title, news.short_text, news.image FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id ORDER BY news.date DESC LIMIT 5");
      
      $i = 0;
      foreach ($newss as $news) {
        $news_html = new HTMLFromFile("templates/single_news.html");
        if (++$i % 2 == 0) {
          $news_html->select(".singlenews")->setAttribute("style", "background: #f1f1f1;");
        }
        $news_html->select(".title")->add($news['title']);
        $news_html->select(".text")->add($news['short_text']);
        $t = strtotime($news['date']);
        $news_html->select(".time")->add(sprintf("%s %s %sr. | %s", date("j", $t), $months[intval(date("n", $t))], date("Y", $t), $news['name']));
        $news_html->select(".readmore_a")->setAttribute("href", "main/view/{$news['news_id']}");
        $content->select("newsbox")->add($news_html);
      }
      return $content;
    }
  }
?>
