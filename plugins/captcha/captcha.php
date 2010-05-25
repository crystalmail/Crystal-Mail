<?php

/**
 * Captcha
 *
 * Plugin provides a captcha challenge
 *
 * @version 1.1 - 25.11.2009
 * @author Roland 'rosali' Liebl
 * @website http://myroundcube.googlecode.com
 * @licence GNU GPL
 *
 **/
 
/**
 * Usage: http://mail4us.net/myroundcube/
 *
 * NOTICE: Captcha Challenge template hook: <roundcube:object name="captcha" />
 *
 **/

/*
   Does emit a CAPTCHA graphic and form fields, which allows to tell real
   people from bots.
   Though a textual description is generated as well, this sort of access
   restriction will knock out visually impaired users, and frustrate all
   others anyhow. Therefore this should only be used as last resort for
   defending against spambots. Because of the readable text and the used
   colorspaces this is a weak implementation, not completely OCR-secure.

   captcha::form () will return a html string to be inserted into textarea/
   [save] <forms> and alike. User input is veryfied with captcha::check ().
   You should leave the sample COLLEGE.ttf next to this script, else you
   have to define the _FONT_DIR constant correctly. Use only 1 font type.

   Creates temporary files, which however get purged automatically after
   four hours.

   Public Domain, available via http://freshmeat.net/p/captchaphp

  Modified to work with WebCalendar by Ray Jones
    Added translation function calls
    Formatting to display properly within our layout
    Disabled textual_riddle function
*/

#-- RoundCube Localization wrapper
function translate($str = ""){
  return rcube_label('captcha.' . str_replace(" ","",strtolower(trim($str))));
}

#-- config
define( 'EWIKI_FONT_DIR', dirname(__FILE__));  // which fonts to use
define( 'CAPTCHA_INVERSE', 0);                 // white or black(=1)
define( 'CAPTCHA_TIMEOUT', 5000);              // in seconds (=max 4 hours)
define( 'CAPTCHA_MAXSIZE', 4500);              // preferred image size
define( 'CAPTCHA_COOKIE', 'captcha_solved');   // to unlock captcha protection
define( 'CAPTCHA_DATA_URLS', 0);               // RFC2397-URLs exclude MSIE users
define( 'CAPTCHA_TEMP_DIR', 'plugins/captcha/temp');

/* static - (you could instantiate it, but...) */
class captcha extends rcube_plugin {

  public $task = "login|mail";

  function init()
  {
    $this->add_texts('localization/');  
    if(file_exists("./plugins/captcha/config/config.inc.php"))
      $this->load_config('config/config.inc.php');
    else
      $this->load_config('config/config.inc.php.dist');
    $this->add_hook('template_object_captcha', array($this, 'show_captcha'));
    $this->add_hook('login_after', array($this, 'check_captcha'));
    $this->add_hook('render_page', array($this, 'check_captcha'));

  }
  
  function show_captcha($p){
  
    $rcmail = rcmail::get_instance();
    $skin = $rcmail->config->get('skin');
    
    if(!file_exists("plugins/captcha/$skin/captcha.css"))
      $skin = "default";

    $this->include_stylesheet('skins/' . $skin . '/captcha.css'); 
    
    $p['content'] = captcha::form();
    return $p;
    
  }

  function get_demo($string){

    $temparr = explode("@",$string);
    return preg_replace ('/[0-9 ]/i', '', $temparr[0]) . "@" . $temparr[count($temparr)-1];

  }
  
  function check_captcha($args){
  
    $rcmail = rcmail::get_instance();
     
    if($rcmail->task == "logout"){
      setcookie(CAPTCHA_COOKIE, '-del-', time() - 60);
    }
    
    // never check on captcha page
    if(
      isset($args['template']) &&
      ($args['template'] == 'captcha' || $rcmail->task == 'logout')
      ){
           
      return $args;
      
    }

    $auth = $rcmail->config->get('captcha_auth');
    
    // check authenticated state
    if(!isset($_SESSION['user_id'])){    
      $page = (array)$rcmail->config->get('captcha_page');
      $page = array_flip($page);
      if(!isset($page[$args['template']])){    
         return $args;
      }
    }

    if(isset($_SESSION['user_id']) && $auth === false){

      return $args;

    }

    // perform the check
    if(captcha::check()){

      return $args;

    }

    // check failed
    if(isset($_POST['captcha_input'])){
      if($this->get_demo($_SESSION['username']) == sprintf($rcmail->config->get('demo_user_account'),"")){
        $rcmail->output->show_message('captcha.captchainput');
      }
      else{
        $rcmail->output->show_message('captcha.captchafailed','error');
      }
    }
    else
      $rcmail->output->show_message('captcha.captchainput');
    $rcmail->output->set_env('keyboard_shortcuts','disabled');  
    $rcmail->output->send('captcha.captcha', 'recur');
    exit;
  
  }

   /* gets parameter from $_REQUEST[] array (POST vars) and so can
      verify input, @returns boolean
   */
   function check () {
      $to = (int)(time ()/1000000);
      if ( ! empty ( $_COOKIE[CAPTCHA_COOKIE] ) && $_COOKIE[CAPTCHA_COOKIE] == $to) {
         return(true);
      }
      if (($hash = $_REQUEST['captcha_hash'])
      and ($pw = trim($_REQUEST['captcha_input']))) {
         $r = (captcha::hash($pw)==$hash) || (captcha::hash($pw,-1)==$hash);
         if ($r) {
            setcookie(CAPTCHA_COOKIE, $to, time ()+1000000);
         }
         return($r);
      }
   }


   /* yields <input> fields html string (no complete form), with captcha
      image already embedded as data:-URI
   */
   function form () {

      #-- stop if user already verified
      if ( ! empty ( $_COOKIE[CAPTCHA_COOKIE] ) &&
        $_COOKIE[CAPTCHA_COOKIE] == (int)(time ()/1000000)) {
        if($_GET['_task'] != 'logout')
          return "";
      }

      $more = translate ( 'Enter security code' );
      #-- prepare image text
      $pw = captcha::mkpass ();
      $hash = captcha::hash($pw);
      $alt = htmlentities(captcha::textual_riddle($pw));

      #-- image
      $img = captcha::image($pw, 200, 60, CAPTCHA_INVERSE, CAPTCHA_MAXSIZE);
      if (CAPTCHA_DATA_URLS && !strpos ('MSIE', $_SERVER['HTTP_USER_AGENT'])) {
         $img_fn = 'data:image/jpeg;base64,' . base64_encode($img);
      }
      else {
         $img_fn = CAPTCHA_TEMP_DIR . '/' . captcha::store_image($img) . '.jpg';
      }

      #-- emit html form
      $html = '
        <script type="text/javascript">
        function hide_riddle(){
          document.getElementById("riddle").style.display="none";
        }
        function show_riddle(){';
        $rcmail = rcmail::get_instance();
        if($rcmail->config->get('captcha_onclick') == 'entity_encoded'){
          $html .= '
          rcmail.display_message(phrase,"notice");';
        }
        else{
          $html .= '
            document.getElementById("riddle").style.display="block";';
        }
        $html .= '
        }        
        </script>
        <div id="captcha">
            <table border="0" summary="captcha input"><tr>
              <td class="title" colspan="2"><small>'.$more.'</small></td></tr><tr>
              <td><img onclick="show_riddle()" onmouseout="hide_riddle()" name="captcha_image" id="captcha_image" src="' .$img_fn.
        '" alt="' .$alt. '" title="' . $alt . '"/></td>
              <td class="title">
                <input name="captcha_hash" type="hidden" value="'.$hash. '" />
                <input name="captcha_input" type="text" size="7" maxlength="16" />
              </td></tr>             
            </table>
            <div id="riddle" style="display:none;"><pre>' . $alt . '</pre></div>
        </div>
      ';
            
      return($html);
   }


   /* generates alternative (non-graphic), human-understandable
      representation of the passphrase
   */
   function textual_riddle($phrase) {
      $rcmail = rcmail::get_instance();
      if($rcmail->config->get('captcha_onclick') == 'entity_encoded'){
   
        $captcha = "";
        for($i=0; $i<strlen($phrase);$i++){
          $captcha .= "&#" . ord(strtoupper(substr($phrase,$i,1))) . ";";
        }
        $rcmail->output->add_script("var phrase=\"" . strtoupper($captcha) . "\";");
      }

      $symbols0 = '"\'-/_:';
      $symbols1 = array ("\n,",
        "\n;",
        ";",
        "\n&",
        "\n-",
        ",",
        ",",
        "\n" . translate("andthen"),
        "\n" . translate("followedby"),
        "\n" . translate("and"),
        "\n" . translate("andnota") . "\n\"".chr(65+rand(0,26))."\",\n" . translate("but"));
      $s = translate("guess") . "\n--\n";
      for ($p=0; $p<strlen($phrase); $p++) {
         $c = $phrase[$p];
         $add = "";
         #-- asis
         if (!rand(0,3)) {
            $i = $symbols0[rand(0,strlen($symbols0)-1)];
            $add = "$i$c$i";
         }
         #-- letter
         elseif ($c >= 'A') {
            $type = ($c >= 'a' ? translate("small") . " " : "");
            do {
               $n = rand(-3,3);
               $c2 = chr((ord($c) & 0x5F) + $n);
            }
            while (($c2 < 'A') || ($c2 > 'Z'));
            if ($n < 0) {
               $n = -$n;
               $add .= "$type'$c2' +$n " . translate("letters");
            }
            else {
               $add .= "$n " . translate("charsbefore") . " $type$c2";
            }
         }
         #-- number
         else {
            $add = "???";
            $n = (int) $c;
            do {
               do { $x = rand(1, 10); } while (!$x);
               $op = rand(0,11);
               if ($op <= 2) {
                  $add = "($add * $x)"; $n *= $x;
               }
               elseif ($op == 3) {
                  $x = 2 * rand(1,2);
                  $add = "($add / $x)"; $n /= $x;
               }
               elseif ( ! empty ( $sel ) && $sel % 2) {
                  $add = "($add + $x)"; $n += $x;
               }
               else {
                  $add = "($add - $x)"; $n -= $x;
               }
            }
            while (rand(0,1));
            $add .= " = $n";
         }
         $s .= "$add";
         $s .= $symbols1[rand(0,count($symbols1)-1)] . "\n";
      }
      return($s);
   }


   /* returns jpeg file stream with unscannable letters encoded
      in front of colorful disturbing background
   */
   function image($phrase, $width=200, $height=60, $inverse=0, $maxsize=0xFFFFF) {

      #-- initialize in-memory image with gd library
      srand(microtime ()*21017);
      $img = imagecreatetruecolor($width, $height);
      $R = $inverse ? 0xFF : 0x00;
      imagefilledrectangle($img, 0,0, $width,$height, captcha::random_color($img, 222^$R, 255^$R));
      $c1 = rand(150^$R, 185^$R);
      $c2 = rand(195^$R, 230^$R);

      #-- configuration
      $fonts = array (
        // "COLLEGE.ttf",
      );
      $fonts += glob(EWIKI_FONT_DIR."/*.ttf");

      #-- encolour bg
      $wd = 20;
      $x = 0;
      $y = $height;
      while ($x < $width) {
         imagefilledrectangle($img, $x, 0, $x+=$wd, $height, captcha::random_color($img, 222^$R, 255^$R));
         $wd += max(10, rand(0, 20) - 10);
      }

      #-- make interesting background I, lines
      $wd = 4;
      $w1 = 0;
      $w2 = 0;
      for ($x=0; $x<$width; $x+=(int)$wd) {
         if ($x < $width) {   // verical
            imageline($img, $x+$w1, 0, $x+$w2, $height-1, captcha::random_color($img,$c1,$c2));
         }
         if ($x < $height) {  // horizontally ("y")
            imageline($img, 0, $x-$w2, $width-1, $x-$w1, captcha::random_color($img,$c1,$c2));
         }
         $wd += rand(0,8) - 4;
         if ($wd < 1) { $wd = 2; }
         $w1 += rand(0,8) - 4;
         $w2 += rand(0,8) - 4;
         if (($x > $height) && ($y > $height)) {
            break;
         }
      }

      #-- more disturbing II, random letters
      $limit = rand(30,90);
      for ($n=0; $n<$limit; $n++) {
         $letter = "";
         do {
            $letter .= chr(rand(31,125)); // random symbol
         } while (rand(0,1));
         $size = rand(5, $height/2);
         $half = (int) ($size / 2);
         $x = rand(-$half, $width+$half);
         $y = rand(+$half, $height);
         $rotation = rand(60, 300);
         $c1  = captcha::random_color($img, 130^$R, 240^$R);
         $font = $fonts[rand(0, count($fonts)-1)];
         imagettftext($img, $size, $rotation, $x, $y, $c1, $font, $letter);
      }

      #-- add the real text to it
      $len = strlen($phrase);
      $w1 = 10;
      $w2 = $width / ($len+1);
      for ($p=0; $p<$len; $p++) {
         $letter = $phrase[$p];
         $size = rand(18, $height/2.2);
         $half = (int) $size / 2;
         $rotation = rand(-33, 33);
         $y = rand($size+3, $height-3);
         $x = $w1 + $w2*$p;
         $w1 += rand(-$width/90, $width/40);  // @BUG: last char could be +30 pixel outside of image
         $font = $fonts[rand(0, count($fonts)-1)];
         $r=rand(30,99); $g=rand(30,99); $b=rand(30,99); // two colors for shadow
         $c1  = imagecolorallocate($img, $r*1^$R, $g*1^$R, $b*1^$R);
         $c2  = imagecolorallocate($img, $r*2^$R, $g*2^$R, $b*2^$R);
         imagettftext($img, $size, $rotation, $x+1, $y, $c2, $font, $letter);
         imagettftext($img, $size, $rotation, $x, $y-1, $c1, $font, $letter);
      }

      #-- let JFIF stream be generated
      $quality = 67;
      $s = array ();
      do {
         ob_start (); ob_implicit_flush(0);
         imagejpeg($img, "", (int)$quality);
         $jpeg = ob_get_contents (); ob_end_clean ();
         $size = strlen($jpeg);
         $s_debug[] = ((int)($quality*10)/10) . "%=$size";
         $quality = $quality * ($maxsize/$size) * 0.93 - 1.7;  // -($quality/7.222)*
      }
      while (($size > $maxsize) && ($quality >= 16));
      imagedestroy($img);
      return($jpeg);
   }


   /* helper code */
   function random_color($img, $a,$b) {
      return imagecolorallocate($img, rand($a,$b), rand($a,$b), rand($a,$b));
   }


   /* creates temporary file, returns basename */
   function store_image($data) {
      $dir = CAPTCHA_TEMP_DIR;
      $id = md5($data);

      #-- create temp dir
      if (!file_exists($dir)) {
         mkdir($dir) && chmod($dir, 0777);
      }

      #-- remove stale files
      if ($dh = opendir($dir)) {
         $t_kill = time () - CAPTCHA_TIMEOUT;
         while($fn = readdir($dh)) if ($fn[0] != ".") {
            if (filemtime("$dir/$fn") < $t_kill) {
               @unlink("$dir/$fn");
            }
         }
      }

      #-- store file
      fwrite($f = fopen("$dir/$id.jpg", 'wb'), $data) && fclose($f);
      return($id);
   }

   /* sends it */
   function get_image($id) {
      $dir = CAPTCHA_TEMP_DIR;
      $fn = "$dir/$id.jpg";
      #-- find it
      if (preg_match('/^\w+$/', $id)) {
         header('Content-Type: image/jpeg');
         readfile($fn);
         exit;
      }
   }
   
   /* unreversable hash from passphrase, with time () slice encoded */
   function hash($text, $dtime=0) {
      $text = strtolower($text);
      $pfix = (int) (time () / CAPTCHA_TIMEOUT) + $dtime;
      return md5("captcha::$pfix:$text::".__FILE__.":$_SERVER[SERVER_NAME]:80");
   }


   /* makes string of random letters for embedding into image and for
      encoding as hash, later verification
   */
   function mkpass () {
      $s = "";
      for ($n=0; $n<10; $n++) {
         $s .= chr(rand(0, 255));
      }
      $s = base64_encode($s);   // base64-set, but filter out unwanted chars
      $s = preg_replace("/[+\/=IG0ODQR]/i", "", $s);  // (depends on YOUR font)
      $s = substr ($s, 0, rand(5,7));
      return($s);
   }
}

#-- IE workaround
if (isset($_REQUEST['_tcf'])) {
   captcha::get_image($_REQUEST['_tcf']);
}


?>