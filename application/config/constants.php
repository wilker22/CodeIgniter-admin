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


define('LOGOMARCA', 							'img/logo.jpg');
define('NM_EMPRESA', 							'Empresa XXX');
define('NM_SISTEMA', 							'Painel Administrativo');
define('EXTENCOES_PERMITIDAS', 					'rar zip exe pdf xlt xls xlsx doc docx txt htm html 3gp mp4 avi flv mp3 swf bmp gif tif jpg cdr psd');
define('LAYOUT_DEFAULT', 						'default');
define('TEMPO_EXPIRAR', 						3600);
define('JS', 									'js/');
define('VENDOR', 								JS.'vendor/');
define('CSS', 									'css/');
define('N_VERSAO', 								'0.1');//.mktime());
define('NO_CACHE', 								'?v='.N_VERSAO);//.mktime());
define('N_ITENS_PAGINA', 				    	50);
define('MSG_SALVO', 						    'Os dados foram salvos. Obrigado!');


define('IMG_RECORTAR', 							'imagens/recortar/[imagem]/?p=[pasta]&t=[class]&r=[class]/[method]/[id]');
define('IMG_THUMB', 							'imagens/thumbnail/[imagem]?p=[pasta]&w=[largura]&h=[altura]');
define('IMG_ERROR', 							'img/error_image.jpg');
define('IMG_CACHE_DIRECTORY',   				realpath(APPPATH.'../cache'));
define('IMG_CACHE_TIME', 						86400);
define('IMG_BROWSER_CACHE',	    				true);
define('IMG_EXT_VALIDAS', 						'jpg|jpeg|png|bmp|swf|gif');
define('IMG_LARG_PADRAO', 						'150');

define('EMAIL_CONTATO', 						'gihovani@gmail.com');
/* End of file constants.php */
/* Location: ./application/config/constants.php */