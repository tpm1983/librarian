<?php

$_WS['GET'] =                      array_keys($_GET);

$_WS['SHAREPOINT_HASH']=           @$_WS['GET'][0];
$_WS_REGEX['SHAREPOINT_HASH']=     '/^[0-9a-z]{4}$/';

$_WS['FILE_HASH']=                 @$_WS['GET'][1];
$_WS_REGEX['FILE_HASH']=           '/^[0-9a-z]{8}$/';

$_WS['BASE_URI']=                   'http://' . $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT']!=80 && $_SERVER['SERVER_PORT']!=443) ? ':' . $_SERVER['SERVER_PORT'] : NULL) . dirname($_SERVER['SCRIPT_NAME']);

$_WS['PATH']=                       dirname($_SERVER['SCRIPT_NAME']) . '/';
$_WS['PATH_FILES']=                 $_WS['PATH'] . 'files/';

$_WS['EXCL_EXT']=                   array('.exe');
$_WS['EXCL_FILES']=                 array('.', '..', '.DS_Store', '.Thumbs');

mysql_select_db(
	// DATABASE
	'librarian', mysql_connect(
		// SERVER
		'localhost',
		// USER
		'root',
		// PASSWORD
		'xx1l3ff'
	)
) or die('Could not select database.');

?>
