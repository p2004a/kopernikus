<?php
  function main_main($params) {
  
    $months = array(1 => "stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
    $weekdays = array(1 => "poniedziałek", "wtorek", "środa", "czwartek", "piątek", "sobota", "niedziela");
  
    if (isset($params[0]) && $params[0] == "view") {
      if (isset($params[1]) && is_numeric($params[1]) && 1 == count(db_query("SELECT * FROM news WHERE news_id = '" . intval($params[1]) . "'"))) {
        $news_id = intval($params[1]);
        
        $newss = db_query("SELECT res.name, news.news_id, news.date, news.title, news.text, news.image FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id WHERE news.news_id = $news_id");
        $news = $newss[0];
      
        $news_html = new HTMLFromFile("templates/single_news.html");
        $news_html->select(".singlenews")->setAttribute("style", "border-top: 1px solid #EBEBEB;");
        $news_html->select(".readmore_a")->setAttribute("href", strip_tags($_SERVER['HTTP_REFERER']));
        $news_html->select(".readmore_a")->clear()->add("Porwót &raquo;");
        
        $news_html->select(".title")->add($news['title']);
        $news_html->select(".text")->add($news['text']);
        $t = strtotime($news['date']);
        $news_html->select(".time")->add(sprintf("%s %s %sr. | %s", date("j", $t), $months[intval(date("n", $t))], date("Y", $t), $news['name']));
        
        return $news_html;
        
      } else {
        
        $out = new HTMLFromFile("templates/subpage.html");
        $out->select(".title")->add("Archiwum wiadomości");
        
        $newss = db_query("SELECT news.news_id, res.name, news.date, news.title FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id ORDER BY news.date DESC");
        
        $ul = new HTMLTag("ul");
        foreach ($newss as $news) {
          $t = strtotime($news['date']);
          $li = new HTMLTag("li", array(), array(
            new HTMLTag('a', array("href" => "main/view/{$news['news_id']}"), new HTMLFromString("<strong>{$news['title']}</strong>")),
            sprintf(" - %s %s %sr. | %s", date("j", $t), $months[intval(date("n", $t))], date("Y", $t), $news['name'])
          ));
          $ul->add($li);
        }
        
        $out->select(".text")->add($ul);
        
        return $out;
        
      }
    } else {
      $content = new HTMLFromFile("templates/news.html");
      
      db_connect();
      
      $infos = db_query("SELECT * FROM view_interestig");
      $ul = new HTMLTag("ul");
      foreach ($infos as $info) {
        $li = new HTMLTag("li", array(), array(
          new HTMLTag('a', array("href" => $info['url'], "target" => $info['target']), $info['title']),
        ));
        $ul->add($li);
      }
      $content->select("vitalnews")->add($ul);
      
      
      $content->select("date")->add(sprintf("%s, %s %s %sr.", $weekdays[date("N")], date("j"), $months[intval(date("n"))], date("Y")));
      
      $names = db_query("SELECT nameday FROM nameday WHERE month = " . date("n") . " AND day = " . date("j"));
      $names = explode("|", $names[0]['nameday']);
      $n1 = rand(0, count($names) - 1);
      while (($n2 = rand(0, count($names) - 1)) == $n1) {}
      $content->select("names")->add(sprintf("%s i %s", $names[$n1], $names[$n2]));
      
      $newss = db_query("SELECT res.name, news.news_id, news.date, news.title, news.short_text, news.text, news.image FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id ORDER BY news.date DESC LIMIT 5");
      
      db_close();
      
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
        if (trim(strip_tags($news['text'])) != "") {
          $news_html->select(".readmore_a")->setAttribute("href", "main/view/{$news['news_id']}");
        } else {
          $news_html->select(".readmore")->hide();
        }
        $content->select("newsbox")->add($news_html);
      }
      return $content;
    }
  }
?>
