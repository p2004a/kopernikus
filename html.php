<?php
  /**
   * @brief Funkcie do generowania HTML
   * 
   * Funkcje i klasy potrzebne do szybkiego i ujednoliconego generowanie kodu
   * HTML, kodu strony
   * Prefiks elementów w pliku: html, HTML
   * @author Marek "p2004a" Rusinowski
   * @file html.php
   */
   
  /**
   * @brief Tablica zawierająca tagi które w html'u nie mają tagów zamykających
   */
  $tags_without_ending_tags = array("area", "base", "basefont", "br", "hr", 
   "img", "input", "isindex", "link", "meta", "nextid", "param");

  interface HTMLObject {
   /**
    * @brief Wybranie obiektu
    * 
    * Wywołana zwraca referencje do obiektu z podanym jako parametr id, jeśli element
    * ma w sobie wiele HTMLObjectów powinien sprawdzić id każdego z nich.
    * Dotatkowo jeśli jest to HTMLTag i $id zawiera kropke to np "align.center"
    * będzie przypasowane do pierwszego HTMLTag'a którego atrubut align ma wartość
    * center, jeśli przed kropka nic nie ma, atrubutem jest "class".
    * @param $id Zawiera id szukanego elementu
    * @return Rererencje do znalezionego obietu lub referencje do HTMLNull gdzy podany element nie istnieje
    */
    public function &select($id);
    
   /**
    * @brief Generuje kod HTML
    *
    * Metoda z obiektu tworzy kod HTML który jest pokazany na stronie.
    * @see HTMLObject::hide()
    * @see HTMLObject::show()
    * @return Ciąg znaków tworzący wygenerowany kod HTML
    */
    public function render();
    
   /**
    * @brief Pobiera id obiektu
    *
    * @return id obiektu lub false jeśli id nie zostało obiektowi ustawione
    */
    public function getId();
    
   /**
    * @brief Ustawia id obiektu
    *
    * @param $id Nowa wartość id dla obiektu
    * @return Referencje do samego siebie
    */
    public function &setId($str);
    
   /**
    * @brief Ukrywa obiekt
    * 
    * Wywołanie tej funkcji na obiekcie ukrywa go to znaczy że gdy obiekt jest niewidoczny
    * i wywołamy na nim funkcję render() powinien zostać zwrucony pusty ciąg znaków
    * @see HTMLObject::render()
    * @see HTMLObject::show()
    * @return Referencje do samego siebie.
    */
    public function &hide();
    
   /**
    * @brief Pokazuje obiekt
    * 
    * Wywołanie tej funkcji na obiekcie pokazuje go, wywołana na nim metoda render()
    * powinna zwrócić "normalny wynik".
    * @see HTMLObject::render()
    * @see HTMLObject::hide()
    * @return Referencje do samego siebie.
    */
    public function &show();
  }

  class HTMLContainer implements HTMLObject {
    protected $objectid;
    protected $visible = true;
    protected $interior;
    
   /**
    * @brief Dodaje dane do wybranego wewnętrznego kontenera objektu
    * 
    * Służy do dodawania objektów do wewnętrznych tablic, przyjmuje HTMLObject
    * które są po prostu dodawane, stringi które są oblekane w HTMLString oraz
    * tablice które są rekurencyjnie rozwijane.
    * @param array &$what Do jakiej tablicy chcemy dołączyć objekt.
    * @param array $object Objekt jaki dodajemy do tablicy
    * @param $back Czy dodajemy na koniec czy na poczatek tablicy
    * @see HTMLContainer::add()
    * @see HTMLPage::addBody()
    * @see HTMLPage::addHead()
    */
    protected function add_to(array &$what, $object, $back = true) {
      $error_msg = "Wrong agrument type passed to " . get_class($this) . "->add_to()"; 
      if (!is_array($object)) {
        $object = array($object);
      }
      foreach ($object as $obj) {
        if (is_string($obj)) $obj = new HTMLString($obj);
        if ($obj instanceof HTMlObject) {
          if ($back) array_push($what, $obj);
          else array_unshift($what, $obj);
        } elseif (is_array($obj)) {
          $this->add_to($what, $obj, $back); 
        } else {
          core_error($error_msg);
        }
      }
    }

    protected function render_array(array &$what) {
      $str_out = "";
      foreach ($what as $htmlobj) {
        $str_out .= $htmlobj->render();
      }
      return $str_out;
    }
    
    public function &add($object, $back = true) {
      $this->add_to($this->interior, $object, $back);
      return $this;
    }
    
    public function &clear() {
      $this->interior = array();
      return $this;
    }

    public function __construct($object = "", $objectid = false) {
      $this->objectid = $objectid;
      $this->interior = array();
      $this->add_to($this->interior, $object);
    }
    
    public function &select($id) {
      if ($id === $this->getId()) {
        return $this;
      }
      foreach ($this->interior as $obj) {
        $obj =& $obj->select($id);
        if ($obj->getId()) {
          return $obj;
        }
      }
      $null = new HTMLNull;
      return $null;
    }
    
    public function getId() {
      return $this->objectid;
    }
    
    public function &setId($srt) {
      $this->objectid = $str;
      return $this;
    }
    
    public function &hide() {
      $this->visible = false;
      return $this;
    }
    
    public function &show() {
      $this->visible = true;
      return $this;
    }

    public function render() {
      if ($this->visible) {
        return $this->render_array($this->interior);
      } else {
        return "";
      }
    }
  } 

  class HTMLPage extends HTMLContainer {
    private $html_body;
    private $html_head;
    
    public function __construct($objectid = false) {
      $this->objectid = $objectid;
      $this->html_body = array();
      $this->html_head = array();
    }
    
    public function &add($object, $back = true) {
      core_warning("add() on HTMLPage object has no efect.");
      return $this;
    }

    public function &addBody($object, $back = true) {
      $this->add_to($this->html_body, $object, $back);
      return $this;
    }
    
    public function &addHead($object) {
      $this->add_to($this->html_head, $object);
      return $this;
    }
    
    public function &loadCSS($uri) {
      $this->addHead(new HTMLTag("link", array("type" => "text/css",  "href" => $uri,  "rel" => "stylesheet")));
      return $this; 
    }
    
    public function &loadJS($uri) {
      $this->addHead(new HTMLTag("script", array("type" => "text/javascript", "src" => $uri)));
      return $this;
    }
    
    public function &clear() {
      $this->html_body = array();
      $this->html_head = array();
      return $this;
    }
    
    public function &select($id) {
      if ($id === $this->getId()) {
        return $this;
      }
      foreach ($this->html_body as $obj) {
        $obj =& $obj->select($id);
        if ($obj->getId()) {
          return $obj;
        }
      }
      foreach ($this->html_head as $obj) {
        $obj =& $obj->select($id);
        if ($obj->getId()) {
          return $obj;
        }
      }
      $null = new HTMLNull;
      return $null;
    }
    
    public function render() {
      if ($this->visible) {
        $out = new HTMLContainer(array(
          '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n",
          new HTMLTag("html", array("xmlns" => "http://www.w3.org/1999/xhtml", "xml:lang" => "en"), array(
            new HTMLTag("head", array(), array(
              new HTMLTag("base", array("href" => $GLOBALS['core_base_root'])),
              new HTMLTag("meta", array("http-equiv" => "content-type", "content" => "text/html; charset=utf-8")),
              $this->html_head
            )),
            new HTMLTag("body", array(), $this->html_body)
          ))
        ));
        return $out->render();
      } else {
        return "";
      }
    }
  }

  class HTMLTag extends HTMLContainer {
    protected $name;
    protected $attribute;
    protected $empty_elem_tag;
   
   /**
    * @brief Konstruktor taga
    * 
    * Konstruktor taga, domyślnie wykrywa czy tag ma zakończenie czy nie. 
    * id oczwiście jeśli podajemy podajemy w tablicy argumentów.
    * @param $name Nazwa taga
    * @param array $attributes Tablica asocjacyjna reprezentujaca atrybuty taga
    * @param $object Tablica zawierająca poczatkowe elementy znajdujące się we wnętrzu taga.
    * @param $empty_elem_tag Jeśli nie chcemy kożystać z domyślnych ustawień możemy zdefiniować czy tag ma zakończenie czy nie.
    * @see $tags_without_ending_tags
    */
    public function __construct($name, array $attributes = array(), $object = array(), $empty_elem_tag = 10) {
      global $tags_without_ending_tags;
      
      if (!is_string($name) || (!is_bool($empty_elem_tag) && $empty_elem_tag != 10)) {
        core_error("Wrong argument type passed to HTMLTag->__construct()");
      }
      $this->name = $name;
      $this->interior = array();
      $this->setAttributes($attributes);
      if ($empty_elem_tag == 10) {
        $this->empty_elem_tag = in_array($name, $tags_without_ending_tags);
      } else {
        $this->empty_elem_tag = $empty_elem_tag;
      }
      $this->add($object);
    }
    
    public function &select($id) {
      $e = explode(".", $id);
      if (count($e) == 2) {
        if ($e[0] == "") {
          $e[0] = "class";
        }
        if ($this->getAttribute($e[0]) == $e[1]) {
          if ($this->getAttribute("id") === false) {
            $this->objectid = true;
          }
          return $this;
        }
      }
      return parent::select($id);
    }
    
   /**
    * @brief Pobiera nazwę taga
    */
    public function getName() {
      return $this->name;
    }

    public function getAttribute($name) {
      if (!isset($this->attribute[$name])) {
        return false;
      }
      return $this->attribute[$name];
    }

    public function &setAttribute($name, $value) {
      if ($name == "id") {
        $this->objectid = $value;
      }
      $this->attribute[$name] = $value;
      return $this;
    }

    public function getAttributes() {
      return $this->attribute;
    }

    public function &setAttributes(array $attributes) {
      if (isset($attributes['id'])) {
        $this->objectid = $attributes['id'];
      } else {
        $this->objetcid = false;
      }
      $this->attribute = $attributes;
      return $this;
    }

    public function render() {
      if ($this->visible) {
        $out = "<" . $this->name;
        foreach ($this->attribute as $name => $value) {
          $out .= " " . $name . "=\"" . $value . "\"";
        }
        if ($this->empty_elem_tag) {
          $out .= " />";
        } else {
          $out .= ">";
          $out .= $this->render_array($this->interior);
          $out .= "</" . $this->name . ">";
        }
        return $out;
      } else {
        return "";
      }
    }
  }
  
  abstract class HTMLSimpleObject implements HTMLObject {
    protected $objectid;
    protected $visible = true;
    
    public function __construct($objectid = false) {
      $this->objectid = $objectid;
    }
    
    public function &select($id) {
      if ($id === $this->getId()) {
        return $this;
      }
      $null = new HTMLNull;
      return $null;
    }
    
    public function getId() {
      return $this->objectid;
    }
    
    public function &setId($str) {
      $this->objectid = $str;
      return $this;
    }
    
    public function &hide() {
      $this->visible = false;
      return $this;
    }
    
    public function &show() {
      $this->visible = true;
      return $this;
    }
    
    public function render() {
      if ($this->visible) {
        return $this->render_visible();
      } else {
        return "";
      }
    }
    
    protected abstract function render_visible();
  }
  
  class HTMLString extends HTMLSimpleObject {
    protected $str;
    
    public function __construct($str, $objectid = false) {
      $this->objectid = $objectid;
      $this->str = $str;
    }
    
    public function &setText($str) {
      $this->str = $str;
      return $this;
    }
    
    public function getText() {
      return $this->str;
    }
    
    public function render_visible() {
      return $this->str;
    }
  }
  
  class HTMLComment extends HTMLString {
    public function render_visible() {
      return "<!-- " . $this->str . " -->";
    }
  }
  
  class HTMLNull implements HTMLObject {
    public function &select($id) {
      return $this;
    }
    
    public function render() {
      return "";
    }
    
    public function getId() {
      return false;
    }
    
    public function &setId($str) {
      return $this;
    }
    
    public function &hide() {
      return $this;
    }
    
    public function &show() {
      return $this;
    }
  }
  
  class HTMLYouTube extends HTMLSimpleObject {
    private $id;
    private $width;
    private $height;
    
    public function __construct($id, $width = 420, $height = 315, $objectid = false) {
      $this->objectid = $objectid;
      $this->id = $id;
      $this->width = $width;
      $this->height = $height;
    }
    
    public function render_visible() {
      $out = new HTMLTag("iframe", array("width" => $this->width, "height" => $this->height, "src" => "http://www.youtube.com/embed/" . $this->id, "frameborder" => "0", "allowfullscreen" => "1"));
      return $out->render();
    }
  }
  
  class HTMLFlash extends HTMLSimpleObject {
    private $url;
    private $height;
    private $width;
    
    public function __construct($url, $width, $height, $objectid = false) {
      $this->objectid = $objectid;
      $this->url = $url;
      $this->width = $width;
      $this->height = $height;
    }
    
    public function render_visible() {
      $object_params = array(
        new HTMLTag("param", array("name" => "movie", "value" => $this->url)),
        new HTMLTag("param", array("name" => "allowScriptAccess", "value" => "sameDomain")),
        new HTMLTag("param", array("name" => "wmode", "value" => "transparent")));
      
      $out = new HTMLTag("object", array("classid" => "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000", "width" => $this->width, "height" => $this->height, "id" => "movie_name", "align" => "middle"), array(
        $object_params,        
        new HTMLComment("[if !IE]>"),
        new HTMLTag("object", array("type" => "application/x-shockwave-flash", "data" => $this->url, "width" => $this->width, "height" => $this->height), array(
          $object_params,
          new HTMLComment("<![endif]"),
          new HTMLTag("a", array("href" => "http://www.adobe.com/go/getflash"), array(
            new HTMLTag("img", array("src" => "http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif", "alt" => "Get Adobe Flash player"))
          )),
          new HTMLComment("[if !IE]>")
        )),
        new HTMLComment("<![endif]")
      ));
      return $out->render();
    }
  }
  
  class HTMLFromString extends HTMLContainer {
    protected $tag_stack;
    
    public function __construct($str, $objectid = false) {
      $this->objectid = $objectid;
      $this->interior = array();
      $this->tag_stack = array(&$this);
      
      
      $str = str_replace("&", "_-=+*^%$#@!;:", $str);
      $str = '<xmldata>' . $str . '</xmldata>';
      $parser = xml_parser_create('utf-8');
      xml_set_object($parser, $this);
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
      xml_set_element_handler($parser, "tag_open", "tag_close");
      xml_set_character_data_handler($parser, "cdata");
      if (!xml_parse($parser, $str)) {
        core_error(sprintf("XML error: %s at line %d",
          xml_error_string(xml_get_error_code($parser)),
          xml_get_current_line_number($parser)));
      }
      xml_parser_free($parser);
    }
    
    protected function tag_open($parser, $name, $attr) {
      if ($name != 'xmldata') {
        foreach ($attr as &$val) {
          $val = str_replace("_-=+*^%$#@!;:", "&", $val);
        }
        $obj = new HTMLTag($name, $attr);
        end($this->tag_stack)->add($obj);
        array_push($this->tag_stack, $obj);
      }
    }
    
    protected function tag_close($parser, $name) {
      if ($name != 'xmldata') {
        array_pop($this->tag_stack);
      }
    }
    
    protected function cdata($parser, $data) {
      $data = str_replace("_-=+*^%$#@!;:", "&", $data);
      $data = (strlen($data) != strlen(ltrim($data)) ? " " : "") . ltrim($data);
      $data = rtrim($data) . (strlen($data) != strlen(rtrim($data)) ? " " : "");
      if (trim($data) != "") {
        end($this->tag_stack)->add($data);
      }
    }
  }
  
  class HTMLFromFile extends HTMLFromString {
    public function __construct($uri, $objectid = false) {
      if(!($file = file_get_contents($uri))) {
        core_error("Cannot open file $uri");
      }
      parent::__construct($file, $objectid);
    }
  }
  
  class HTMLCKEditor extends HTMLSimpleObject {
    private $name;
    private $width;
    private $height;
    private $toolbar;
    private $content;
    
    public function __construct($name, $toolbar = "Basic", $content = "", $width = "100%", $height = "300") {
      $this->name = $name;
      $this->width = $width;
      $this->height = $height;
      $this->toolbar = $toolbar;
      $this->content = $content;
      
      if (!isset($GLOBALS['ckeditor_js_loaded'])) {
        $GLOBALS['ckeditor_js_loaded'] = true;
        $GLOBALS['html']->loadJS("ckeditor/ckeditor.js");
      }
    }
    
    public function render_visible() {
      $out = new HTMLContainer(array(
        new HTMLTag("textarea", array("name" => $this->name, "id" => $this->name, "width" => $this->width, "height" => $this->height), htmlspecialchars($this->content)),
        new HTMLTag("script", array("type" => "text/javascript"), "CKEDITOR.replace('{$this->name}',{contentsCss:'assets/output_xhtml.css',toolbar:'{$this->toolbar}'});")
      ));
      
      return $out->render();
    }
  }
  
  abstract class HTMLFacebookObject extends HTMLSimpleObject {
    protected function init_facebook() {
      if (!isset($GLOBALS['facebook_sdk_loaded'])) {
        $GLOBALS['facebook_sdk_loaded'] = true;
        $GLOBALS['html']->loadJS('js/facebook.js');
        if (isset($GLOBALS['conf_fb_app_id'])) {
          $app_id = '"' . $GLOBALS['conf_fb_app_id'] . '"';
        } else {
          $app_id = 'null';
        }
        $GLOBALS['html']->addBody('
          <div id="fb-root"></div>
          <script>
            window.fbAsyncInit = function() {
              FB.init({
                appId      : ' . $app_id . ',
                channelUrl : "' . $GLOBALS['core_base_root'] . 'channel",
                status     : true,
                xfbml      : true
              });
              
              facebook_main()
            };

            // Load the SDK asynchronously
            (function(d, s, id){
               var js, fjs = d.getElementsByTagName(s)[0];
               if (d.getElementById(id)) {return;}
               js = d.createElement(s); js.id = id;
               js.src = "//connect.facebook.net/en_US/all.js";
               fjs.parentNode.insertBefore(js, fjs);
             }(document, "script", "facebook-jssdk"));
          </script>        
        ', false);
      }
    }
  }
  
  class HTMLFacebookLike extends HTMLFacebookObject {
    private $url;
    
    public function __construct($url) {
      $this->url = $url;
      $this->init_facebook();
    }
    
    public function render_visible() {
      $out = new HTMLTag("fb:like", array("href" => $GLOBALS['core_base_root'] . $this->url, "send" => "false", "layout" => "button_count", "width" => "450", "show_faces" => "false"));
      
      return $out->render();
    }
  }
  
  class HTMLFacebookComments extends HTMLFacebookObject {
    private $url;
    private $width;
    private $num_posts;
    
    public function __construct($url, $width, $num_posts = 10) {
      $this->url = $url;
      $this->width = $width;
      $this->num_posts = $num_posts;
      
      $this->init_facebook();
    }
    
    public function render_visible() {
      $out = new HTMLTag("fb:comments", array("href" => $GLOBALS['core_base_root'] . $this->url, "width" => $this->width, "num_posts" => $this->num_posts));
      
      return $out->render();
    }
  }
  
  class HTMLGooglePlusOne extends HTMLSimpleObject {
    private $url;
    
    public function __construct($url) {
      $this->url = $url;
      
      if (!isset($GLOBALS['google_plusone_loaded'])) {
        $GLOBALS['google_plusone_loaded'] = true;
        $GLOBALS['html']->addHead(new HTMLTag("script", array("type" => "text/javascript", "src" => "https://apis.google.com/js/plusone.js"), "{lang: 'pl'}"));
      }
    }
    
    public function render_visible() {
      $out = new HTMLTag("g:plusone", array("href" => $GLOBALS['core_base_root'] . $this->url, "size" => "small"));
      
      return $out->render();
    }
  }

  $html = new HTMLPage();
?>
