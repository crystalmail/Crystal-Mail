<title>Crystal Mail Installer :: Step 1:: Check Environment</title>
<center>
<ol id="progress">
<li class="step2"><in_progress>Check environment</in_progress></li><li class="step3">Generate config</li><li class="step4">Check install</li></ol>
</center>
<div id="rounded">
<form action="index.php" method="get">
<?php
function __autoload($classname)
{
  $filename = preg_replace(
      array('/MDB2_(.+)/', '/Mail_(.+)/', '/Net_(.+)/', '/^html_.+/', '/^utf8$/'),
      array('MDB2/\\1', 'Mail/\\1', 'Net/\\1', 'html', 'utf8.class'),
      $classname
  );
  include_once $filename. '.php';
}


/**
 * Fake internal error handler to catch errors
 */


ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('display_errors', 1);

define('INSTALL_PATH', realpath(dirname(__FILE__) . '/../').'/');
define('RCMAIL_CONFIG_DIR', INSTALL_PATH . 'config');

$include_path  = INSTALL_PATH . 'program/lib' . PATH_SEPARATOR;
$include_path .= INSTALL_PATH . 'program' . PATH_SEPARATOR;
$include_path .= INSTALL_PATH . 'program/include' . PATH_SEPARATOR;
$include_path .= ini_get('include_path');

set_include_path($include_path);

require_once 'main.inc';



$CWI = crystal_install::get_instance();
$CWI->load_config();


$required_php_exts = array(
    'PCRE'      => 'pcre',
    'DOM'       => 'dom',
    'Session'   => 'session',
    'XML'       => 'xml',
    'JSON'      => 'json'
);

$optional_php_exts = array(
    'FileInfo'  => 'fileinfo',
    'Libiconv'  => 'iconv',
    'Multibyte' => 'mbstring',
    'OpenSSL'   => 'openssl',
    'Mcrypt'    => 'mcrypt',
);

$required_libs = array(
    'PEAR'      => 'PEAR.php',
    'MDB2'      => 'MDB2.php',
    'Net_SMTP'  => 'Net/SMTP.php',
    'Mail_mime' => 'Mail/mime.php',
);

$supported_dbs = array(
    'MySQL'         => 'mysql',
    'MySQLi'        => 'mysqli',
    'PostgreSQL'    => 'pgsql',
    'SQLite (v2)'   => 'sqlite',
);

$ini_checks = array(
    'file_uploads'                  => 1,
    'session.auto_start'            => 0,
    'zend.ze1_compatibility_mode'   => 0,
    'mbstring.func_overload'        => 0,
    'suhosin.session.encrypt'       => 0,
);

$optional_checks = array(
    'date.timezone' => '-NOTEMPTY-',
);

$source_urls = array(
    'Sockets' => 'http://www.php.net/manual/en/book.sockets.php',
    'Session' => 'http://www.php.net/manual/en/book.session.php',
    'PCRE' => 'http://www.php.net/manual/en/book.pcre.php',
    'FileInfo' => 'http://www.php.net/manual/en/book.fileinfo.php',
    'Libiconv' => 'http://www.php.net/manual/en/book.iconv.php',
    'Multibyte' => 'http://www.php.net/manual/en/book.mbstring.php',
    'Mcrypt' => 'http://www.php.net/manual/en/book.mcrypt.php',
    'OpenSSL' => 'http://www.php.net/manual/en/book.openssl.php',
    'JSON' => 'http://www.php.net/manual/en/book.json.php',
    'DOM' => 'http://www.php.net/manual/en/book.dom.php',
    'PEAR' => 'http://pear.php.net',
    'MDB2' => 'http://pear.php.net/package/MDB2',
    'Net_SMTP' => 'http://pear.php.net/package/Net_SMTP',
    'Mail_mime' => 'http://pear.php.net/package/Mail_mime',
);

echo '<input type="hidden" name="step" value="' . ($CWI->configured ? 2 : 2) . '" />';
?>

<h3>Checking PHP version</h3>
<?php

define('MIN_PHP_VERSION', '5.2.0');
if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '>=')) {
    $CWI->pass('Version', 'PHP ' . PHP_VERSION . ' detected');
} else {
    $CWI->fail('Version', 'PHP Version ' . MIN_PHP_VERSION . ' or greater is required ' . PHP_VERSION . ' detected');
}
?>

<h3>Checking PHP extensions</h3>
<p class="hint">The following modules/extensions are <em>required</em> to run RoundCube:</p>
<?php

// get extensions location
$ext_dir = ini_get('extension_dir');

$prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
foreach ($required_php_exts AS $name => $ext) {
    if (extension_loaded($ext)) {
        $CWI->pass($name);
    } else {
        $_ext = $ext_dir . '/' . $prefix . $ext . '.' . PHP_SHLIB_SUFFIX;
        $msg = @is_readable($_ext) ? 'Could be loaded. Please add in php.ini' : '';
        $CWI->fail($name, $msg, $source_urls[$name]);
    }
    echo '<br />';
}

?>

<p class="hint">The next couple of extensions are <em>optional</em> and recommended to get the best performance:</p>
<?php

foreach ($optional_php_exts AS $name => $ext) {
    if (extension_loaded($ext)) {
        $CWI->pass($name);
    }
    else {
        $_ext = $ext_dir . '/' . $prefix . $ext . '.' . PHP_SHLIB_SUFFIX;
        $msg = @is_readable($_ext) ? 'Could be loaded. Please add in php.ini' : '';
        $CWI->na($name, $msg, $source_urls[$name]);
    }
    echo '<br />';
}

?>


<h3>Checking available databases</h3>
<p class="hint">Check which of the supported extensions are installed. At least one of them is required.</p>

<?php

$prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
foreach ($supported_dbs AS $database => $ext) {
    if (extension_loaded($ext)) {
        $CWI->pass($database);
    }
    else {
        $_ext = $ext_dir . '/' . $prefix . $ext . '.' . PHP_SHLIB_SUFFIX;
        $msg = @is_readable($_ext) ? 'Could be loaded. Please add in php.ini' : 'Not installed';
        $CWI->na($database, $msg, $source_urls[$database]);
    }
    echo '<br />';
}

?>


<h3>Check for required 3rd party libs</h3>
<p class="hint">This also checks if the include path is set correctly.</p>

<?php

foreach ($required_libs as $classname => $file) {
    @include_once $file;
    if (class_exists($classname)) {
        $CWI->pass($classname);
    }
    else {
        $CWI->fail($classname, "Failed to load $file", $source_urls[$classname]);
    }
    echo "<br />";
}


?>

<h3>Checking php.ini/.htaccess settings</h3>
<p class="hint">The following settings are <em>required</em> to run RoundCube:</p>

<?php

foreach ($ini_checks as $var => $val) {
    $status = ini_get($var);
    if ($val === '-NOTEMPTY-') {
        if (empty($status)) {
            $CWI->fail($var, "cannot be empty and needs to be set");
        } else {
            $CWI->pass($var);
        }
        echo '<br />';
        continue;
    }
    if ($status == $val) {
        $CWI->pass($var);
    } else {
      $CWI->fail($var, "is '$status', should be '$val'");
    }
    echo '<br />';
}
?>

<p class="hint">The following settings are <em>optional</em> and recommended:</p>

<?php

foreach ($optional_checks as $var => $val) {
    $status = ini_get($var);
    if ($val === '-NOTEMPTY-') {
        if (empty($status)) {
            $CWI->optfail($var, "Could be set");
        } else {
            $CWI->pass($var);
        }
        echo '<br />';
        continue;
    }
    if ($status == $val) {
        $CWI->pass($var);
    } else {
      $CWI->optfail($var, "is '$status', could be '$val'");
    }
    echo '<br />';
}
?>

<?php

if ($CWI->failures) {
  echo '<p class="warning">Sorry but your webserver does not meet the requirements for Crystal Webmail! :(<br />
            Please install the missing modules or fix the php.ini settings according to the above check results.<br />
            Hint: only checks showing <span class="fail">NOT OK</span> need to be fixed.</p>';
}
echo '<p id="button"><input type="submit" value="NEXT" ' . ($CWI->failures ? 'disabled' : '') . ' /></p><br>';

?>

</form>
