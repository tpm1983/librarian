<?php

$_WS['GET'] = array_keys($_GET);

$_WS['HASH']=			@$_WS['GET'][0];
$_WS['HASH_REGEX']=		'/^[0-9a-z]{6}$/';

$_WS['BASE_URI']=		'https://' . $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT']!=80 && $_SERVER['SERVER_PORT']!=443) ? ':' . $_SERVER['SERVER_PORT'] : NULL) . '/';

$_WS['PATH']=			$_SERVER['DOCUMENT_ROOT'] . '/';
$_WS['PATH_FILES']=		$_WS['PATH'] . 'files/';

$_WS['EXCL_EXT']=		array('.exe');
$_WS['EXCL_FILES']=		array('.', '..', '.DS_Store', '.Thumbs');

$_WS['SQL_CONNECT'][]=	@mysql_connect(

	 // HOST
	'sql.bembel.de',

	// USER
	'root',

	 // PASSWORD
	'g37my31s!'
);

?>