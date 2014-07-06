<?php
class Language {

  public $lang      = null;
  public $directory = null;

  public function __construct($dir)
  {
    if(is_dir($dir) && count(scandir($dir)) > 0)
    {
      $this->directory = $dir;
      return true;
    }
    else
      return false;
  }

  public function defaultLanguage($lang)
  {
    $this->lang = strtolower($lang);
    return $this;
  }

  public function getCountryByIP($bool = false)
  {
    if($bool === true)
    {
      $find       = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}"));
      $country    = strtolower($find->country);

      switch($country)
      {
        case 'us':
        case 'gb':
        case null:
        $this->lang = 'en'; // Fix for the "EN" language pack, america = us, united kingdom = gb
        break;
        default:
        $this->lang = $country;
        break;
      } // End of switch statement

    }
    else  // Our boolean is false!
        return false;
  }

  public function bind($parent_word) 
  {
    $lang = parse_ini_file(sprintf("%s%s.ini.php", $this->directory, $this->lang));

    $find = explode("->", $parent_word);
    
    return $lang[$find[0]][$find[1]];
  }

}

$language = new Language("language_pack/");
$language->defaultLanguage("EN")->getCountryByIP(false);

// Usage
echo "<h5> {$language->bind("message->welcome")} </h5>";

