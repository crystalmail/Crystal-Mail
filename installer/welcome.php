<title>Crystal Mail Installer :: Welcome</title>
</center>

<div id="rounded"><h1><center>Welcome to the Crystal Webmail Installer</h1>
<br>
<center>
<p>You are just moments away from installing the best webmail client ever!<br />
The interactive installer will guide you through the entire installation and even write the configuration files. Just sit back, configuration should take less than 10 minutes!</p>
</center> 

<br /><br />

<div id="reqs">
<p class="bold">Basic Requirements for Crystal Webmail:</p>
<ul>
	<li>PHP Version 5.2.0 or greater including
    	<ul>
			<li>PCRE (perl compatible regular expression)</li>
			<li>Session support</li>
			<li>Libiconv (recommended)</li>
			<li>OpenSSL (recommended)</li>
			<li>FileInfo (optional)</li>
			<li>Multibyte/mbstring (optional)</li>
			<li>Mcrypt (optional)</li>
		</ul>
	</li>
	<li>php.ini options:
	    <ul>
	        <li>error_reporting E_ALL &amp; ~E_NOTICE (or lower)</li>
	        <li>file_uploads on (for attachment upload features)</li>
	        <li>session.auto_start needs to be off</li>
	    </ul>
	</li>
	<li>A MySQL or PostgreSQL database engine or the SQLite extension for PHP</li>
	<li>An SMTP server (recommended) or PHP configured for mail delivery</li>
</ul>
</div>
<!-- <div id="button"><input type=button onClick="location.href='index.php?step=1'" value='Continue'></div> -->
<form action="index.php" methond="get">
<input type="hidden" name="_step" value="1" />
<div id="button"><input type=submit value='Begin Install'></div> 
<br>
</div>
