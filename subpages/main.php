<?php
  function main_main($params) {
    $content = new HTMLFromFile("templates/news.html");
    $newss = db_query("SELECT res.name, news.date, news.title, news.text, news.image FROM news INNER JOIN (SELECT user_id, name FROM users UNION SELECT user_id, name FROM deleted_users) AS res ON news.user_id = res.user_id ORDER BY news.date DESC LIMIT 5");
    
    $months = array(1 => "stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
    
    $i = 0;
    foreach ($newss as $news) {
      $news_html = new HTMLFromFile("templates/single_news.html");
      if (++$i % 2 == 0) {
        $news_html->select(".singlenews")->setAttribute("style", "background: #f1f1f1;");
      }
      $news_html->select(".title")->add($news['title']);
      $news_html->select(".text")->add($news['text']);
      $t = strtotime($news['date']);
      $news_html->select(".time")->add(sprintf("%s %s %sr. | %s", date("j", $t), $months[intval(date("n", $t))], date("Y", $t), $news['name']));
      $content->select("newsbox")->add($news_html);
    }
    
    return $content;
  }
?>
