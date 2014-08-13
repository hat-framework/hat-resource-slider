<?php

use classes\Classes\JsPlugin;
class easysliderJs extends JsPlugin{
    
    private $configuration = array('time' => '3000', 'speed' => '1000');
    private $title         = "";
    private $url_artigo    = "";
    private $cid           = "";
    private $iid           = "";
    private $width         = '80';
    private $height        = '80';
    public function init(){
        $this->Html->loadExternCss("$this->url/css/screen");
        $this->Html->LoadJs("$this->url/js/easySlider1.7");
    }

    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name($plugin);
        }
        return self::$instance;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setUrlController($url){
        $this->url_artigo = $url;
    }

    public function setItemID($id){
        $this->iid = $id;
    }

    public function setConteudoID($id){
        $this->cid = $id;
    }


    public function slider($artigo, $id = 'slider', $class = "slider", $base = CURRENT_MODULE, $config = array()){
        
          if($this->drawArtigo($artigo, $id, $class, $base)){

              $config = array_merge($config, $this->configuration);
              extract($config);
              $this->load($id, "#", $time, $speed);
              
          }
    }  

    public function drawArtigo($artigo, $id, $class, $img_size = "min"){

        if(empty($artigo)) return false;
        $image = "";
        $title = ($this->title == "")?"":"<h3>$this->title</h3>";
        $class = ($class == "")?"easyslider":"$class easyslider";
        echo "\n\t<div class='$class' id='$id'> $title";
              echo "\n\t\t <ul>";
              $var = "";
                  foreach($artigo as $art){
                    $this->LoadComponent("galeria/album/album", 'aobj');
                    $image = $this->aobj->getLinkCapa($art['album'], $img_size, false);
                    if($image == ""){
                        $image = "<div class='imgvazia'></div>";
                        $num   = 120;
                        $id    = 'longtitle';
                    }else{
                        $image = "<img src='$image'/>";
                        $num   = 80;
                        $id    = 'title';
                    }
                    $url = $this->Html->getLink($this->url_artigo.$art[$this->iid] . "/" . GetPlainName($art[$this->cid]));
                    $titulo = Resume($art[$this->cid], $num);//strtolower(Resume($art[$this->cid], $num));
                    //$titulo = ucfirst($titulo);
                    echo "\n\t\t\t<li><a href='$url' class='img'>$image<div id='$id'>$titulo</div></a></li>";
                  }
              echo "\n\t\t</ul>";
        echo "\n\t</div>";
        return true;
    }

    public function configure($width, $heigth){
        $this->width  = $width;
        $this->height = $heigth;
    }

    public function drawFotos($album, $id, $title, $class){
        $title = ($this->title == "")?$title:"<h2>$this->title</h2>";

        $this->LoadComponent("galeria/foto/foto", 'aobj');
        $this->LoadModel("galeria/album", 'galbum');
        $album  = (is_array($album))?$album: $this->galbum->getItem($album);
        if(empty ($album)) return;
        
        $images = $this->galbum->getFotos($album['cod_album']);
        
        echo "\n\t<div class='$class slider easyslider' id='$id' > $title";
              echo "\n\t\t <ul>";
                  foreach($images as $img){
                    $url   = $this->aobj->getUrl($img, "");
                    $image = $this->aobj->getUrl($img, "max");
                    $image = "<img src='$image'/>";
                    echo "\n\t\t\t<li><a href='$url' class='img'>$image</a></li>";
                  }
              echo "\n\t\t</ul>";
        echo "\n\t</div>";
        
        
        $this->load($id);
        return true;
    }
    
    private function load($id = 'easyslider', $type = "#", $time = 5000, $speed = 1500){
        $this->Html->LoadJqueryFunction("
              $('$type$id').show(1500, function() {
                   $('$type$id').easySlider({
                        auto: true,
                        continuous: true,
                        nextText: '',
                        numeric: false,
                        pause: $time,
                        prevText: '',
                        speed: $speed
                    });
               });");
    }
    
}

?>