<?php
/**
 *  WordAi Module
 *
 *  @author Ivan Milincic <lokomotivan@gmail.com>
 *  @copyright 2018 Ivan Milincic
 *
 *
*/

class WordAi extends WireData implements Module {

  public static function getModuleInfo() {
      return array(
          'title' => 'WordAi',
          'version' => 100,
          'summary' => 'WordAi text/article spinner.',
          'author' => 'Ivan Milincic',
          'icon' => 'font',
          'singular' => true,
          'autoload' => false
      );
  }

  public function init() {
    // run on init
  }

  /**
   *  Main WordAi method
   *  Send request using cURL,
   *  @var text       string, text to be replaced
   *
   */
  public function request($text) {

    $POST   = "s=$text&email=$this->email";
    $POST   .= (!empty($this->hash)) ? "&hash=$this->hash" : "&pass=$this->pass";
    $POST   .= "&quality=$this->quality";
    $POST   .= ($this->output == "json") ? "&output=json" : "";
    $POST   .= ($this->returnspin == "true") ? "&returnspin=true" : "";
    $POST   .= ($this->nooriginal == "on") ? "&nooriginal=on" : "";
    $POST   .= ($this->sentence == "on") ? "&sentence=on" : "";
    $POST   .= ($this->paragraph == "on") ? "&paragraph=on" : "";
    $POST   .= ($this->title == "on") ? "&title=on" : "";
    $POST   .= (!empty($this->synonyms)) ? "&synonyms=$this->synonyms" : "";


    if((!empty($this->pass) || !empty($this->hash)) && !empty($this->email) && !empty($text) && !empty($this->quality)) {

      $text = urlencode($text);

      $ch = curl_init('http://wordai.com/users/turing-api.php');

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt ($ch, CURLOPT_POST, 1);

      curl_setopt ($ch, CURLOPT_POSTFIELDS, "$POST");

      $result = curl_exec($ch);

      curl_close ($ch);

      return $result;

    }

    else {

      if(empty($this->email)) $this->error("Email is missing");
      if(empty($this->hash) && empty($this->pass)) $this->error("Hash and password is missing");
      if(empty($text)) $this->error("Text is missing");

    }

  }

  /**
   *  Process Spintax strings
   *  Use regex to find {String|String}
   *  Use @method replace to replace words
   *  @var text
   *
   */
  public function process($text){
    return preg_replace_callback(
      '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
      array($this, 'replace'),
      $text
    );
  }

  /**
   *  Replace
   *  @var text
   *  This will run in process @method
   *
   */
  private function replace($text) {
    $text = $this->process($text[1]);
    $parts = explode('|', $text);
    return $parts[array_rand($parts)];
  }


  public function getText($text) {
    $new_text = $this->request($text);
    return $this->process($new_text);
  }

}
