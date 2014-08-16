<?php 
$config['base_url'] = 'http://login.geek911.dk/'; // Base URL including trailing slash (e.g. http://localhost/)

$config['default_controller'] = 'HomeController'; // Default controller to load
$config['default_action'] = 'initAction'; // Default action in deafult controller to load	

$config['db_host'] = 'mysql8.unoeuro.com'; // Database host (e.g. localhost)
$config['db_name'] = 'geek911_dk_db'; // Database name
$config['db_username'] = 'geek911_dk'; // Database username
$config['db_password'] = 'nummer12'; // Database password
$config['db_charset'] = 'utf8'; // PDO charset

$config['mail_from_mail'] = 'noreply@login.dk'; // Mail 
$config['mail_from_name'] = 'login'; // Mail 
$config['mail_host'] = 'smtp.unoeuro.com'; // Mail
$config['mail_wordwrap'] = 50; // Mail 
$config['mail_charset'] = 'UTF-8'; // Mail 
$config['mail_contenttype'] = "text/html"; // Mail

$config['mail_isSMTP'] = false; // Mail 
$config['mail_SMTPDebug'] = 1;// enables SMTP debug information (for testing)
                                  // 1 = errors and messages
                                  // 2 = messages only
/*
// if SMTP Authentification is required
$config['mail_SMTPAuth'] = true; // Mail
$config['mail_port'] = 26; // Mail 
$config['mail_username'] = "support@login.dk"; // Mail
$config['mail_password'] = "Nummer12"; // Mail
*/

