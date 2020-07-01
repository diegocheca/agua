<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');



define('tarifa_social','24.00');
define('precio_mt_fam','4.30');
define('tarifa_familiar','80.00');
define('mts_basicos_familiar','10');

define('precio_mt_com','7.30');
define('tarifa_comercial','150.00');
define('mts_basicos_comercio','15');

define('cuota_social','24.00');

define('color_pago','style="background-color:#3F51B5;"');


define('puesto','1');
date_default_timezone_set('America/Argentina/San_Juan');
define('hoy',date("Y-m-d"));
define('hora',date("H:i:s"));
define('cantidad_disponible',5);
define('cantidad_digitos',12);


/* End of file constants.php */
/* Location: ./application/config/constants.php */