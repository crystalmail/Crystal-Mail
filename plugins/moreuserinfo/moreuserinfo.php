<?php
/**
 * moreuserinfo plugin
 *
 *
 * @version 1.0 - 21.06.2009
 * @author Roland 'rosali' Liebl
 * @website http://mycrystalmail.googlecode.com
 * @licence GNU GPL
 *
 **/

/**
 *
 * Usage: http://mail4us.net/mycrystalmail/
 *
 **/ 

class moreuserinfo extends crystal_plugin
{

  public $task = 'mail|settings';

  function init()
  {
    $this->add_texts('localization/');  

    if(file_exists("./plugins/moreuserinfo/config/config.inc.php"))
      $this->load_config('config/config.inc.php');
    else
      $this->load_config('config/config.inc.php.dist');
      
    $this->register_action('plugin.moreuserinfo_show', array($this, 'frame'));   
    $this->register_action('plugin.moreuserinfo', array($this, 'infostep'));
    $this->add_hook('render_page', array($this, 'showuser'));
    $cmail = cmail::get_instance();
    if ($cmail->task == 'settings') {
      $dont_override = $cmail->config->get('dont_override', array());
      if (!in_array('date_long', $dont_override)) {
        $this->add_hook('user_preferences', array($this, 'prefs_table'));
        $this->add_hook('save_preferences', array($this, 'save_prefs'));
      }
    }
  }
 
  function frame()
  {
    $cmail = cmail::get_instance();
    $cmail->output->add_script("cmail.add_onload(\"cmail.sections_list.select('accountlink')\");");
    $cmail->output->add_script("cmail.add_onload(\"document.frames['prefs-frame'].location.href='./?_task=settings&_action=plugin.moreuserinfo&_framed=1'\");");       
    $cmail->output->send("settings");
    exit;
  }
  
  function prefs_table($args)
  {
    if ($args['section'] == 'general') {
      $this->add_texts('localization');
      $cmail = cmail::get_instance();
      $formats = $cmail->config->get('date_formats');
      if(!is_array($formats))
        $formats = array('d.m.Y H:i','Y-m-d H:i','m-d-Y H:i');
      $field_id = 'rcmfd_dateformat';
      $select = new html_select(array('name' => '_date_long', 'id' => $field_id));
      $select->add($formats);
      $args['blocks']['main']['options']['dateformat']['title'] = Q($this->gettext('moreuserinfo.dateformat'));
      $args['blocks']['main']['options']['dateformat']['content'] = $select->show($cmail->config->get('date_long'), array('name' => "_date_long"));
      
    }

    return $args;

  }

  function save_prefs($args)
  {
    if($args['section'] == 'general'){
      $args['prefs']['date_long'] = get_input_value('_date_long', crystal_INPUT_POST);
      return $args;
    }
  }

  function infostep()
  {
    $this->register_handler('plugin.moreuserinfo_html', array($this, 'infohtml'));
    cmail::get_instance()->output->send('moreuserinfo.moreuserinfo');
  }

  function showuser($p)
  {
  
    if($p['template'] != "mail")
      return $p;

    $cmail = cmail::get_instance();  

    if(isset($_SESSION['temp']) || isset($_SESSION['terms']) || strtolower($cmail->task) != "mail")
      return $p; 

    $skin  = $cmail->config->get('skin');
    $_skin = get_input_value('_skin', crystal_INPUT_POST);

    if($_skin != "")
      $skin = $_skin;

    // abort if there are no css adjustments
    if(!file_exists('plugins/moreuserinfo/skins/' . $skin . '/moreuserinfo.css')){
      if(!file_exists('plugins/moreuserinfo/skins/default/moreuserinfo.css'))   
        return $p;
      else
        $skin = "default";
    }

    $this->include_stylesheet('skins/' . $skin . '/moreuserinfo.css');
    $browser = new crystal_browser;
    if($browser->ie && $browser->ver == 6){
      $this->include_stylesheet('skins/' . $skin . '/ie6.css');	
    }
    
    $user = $cmail->user->data['username'];
    if(strlen($user) > 20)
      $user = substr($user,0,20) . "...";
      
    $plugins = $cmail->config->get("plugins");
    $plugins = array_flip($plugins);
    if(!isset($plugins['accounts']))
      $cmail->output->add_footer('<div id="showusername"><a title="' . $this->gettext('userinfo', 'moreuserinfo') . '" href="./?_task=settings&_action=plugin.moreuserinfo_show">' . $user . '</a></div>');

    return $p;

  }

  function infohtml()
  {
    $cmail = cmail::get_instance();

    $user = $cmail->user;
    
    $table = new html_table(array('cols' => 2, 'cellpadding' => 3));

    $table->add('title', Q($this->gettext('imapserver')));
    $imap_host = $_SESSION['imap_host'];    
    if(strtolower($imap_host) == "localhost")
      $imap_host = $cmail->config->get('real_localhost_imap');
    if(!empty($_SESSION['imap_ssl']))  
      $imap_host = "ssl://" . $imap_host;
    $table->add('', Q($imap_host));

    $table->add('title', Q($this->gettext('port')));
    $table->add('', Q($_SESSION['imap_port']));

    $table->add('title', Q($this->gettext('smtpserver')));
    $smtp_host = $cmail->config->get('smtp_server');

    if(strtolower($smtp_host) == "localhost")
      $smtp_host = $cmail->config->get('real_localhost_smtp');
    $table->add('', Q($smtp_host));
    $table->add('title', Q($this->gettext('port')));
    $table->add('', Q($cmail->config->get('smtp_port')));

    $date_format = $cmail->config->get('date_long');
    if(preg_match('/%[a-z]+/i', $date_format))   
      $date_format = 'Y-m-d H:i';
    if(!$date_format)
      $date_format = 'Y-m-d H:i';
      
    $created = new DateTime($user->data['created']);
    $table->add('title', Q($this->gettext('created')));
    $table->add('', Q(date_format($created, $date_format)));
    $lastlogin = new DateTime($user->data['last_login']);
    $table->add('title', Q($this->gettext('lastlogin')));
    $table->add('', Q(date_format($lastlogin, $date_format)));

    $identity = $user->get_identity();
    $table->add('title', Q($this->gettext('defaultidentity')));
    $table->add('', Q($identity['name'] . ' <' . $identity['email'] . '>'));

    $out  = $out .= '<fieldset><legend>' . $this->gettext('userinfo') . ' ::: ' . $_SESSION['username'] . '</legend>' . "\n";
    $out .= $table->show();
    $out .= "</fieldset>\n";
    
    $out .= "<p>&nbsp;<input type='button' onclick='document.location.href=\"./?_task=settings&_action=edit-prefs&_section=accountlink&_framed=1\"' class='button' value='" . Q($this->gettext('back')) ."' /></p>\n";

    return $out;
  }

}

?>