<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination

require_once 'include/config.php';
require_once 'include/functions.php';

mysql_select_db('webshare', $_WS['SQL_CONNECT'][0]) or die('Could not select database.');

$s_id= intval($_GET['id']);
$f_hash=false;
$return=false;

for ($f_hash=_ws_mkhash(); $return == true; $f_hash=_ws_mkhash()) :

	$query = mysql_query("SELECT f_id FROM files WHERE f_hash = '" . $f_hash . "'");
	if (mysql_num_rows($query)==0) $return= true;
endfor;

if (!empty($_FILES) or @$_GET['debug']) :

	if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $_WS['PATH_FILES'] . $f_hash)  or @$_GET['debug']) :

		mysql_query("INSERT INTO files (
			f_hash,
			s_id,
			status,
			name,
			mime,
			extension,
			size,
			time_create
		) VALUES (
			'" . $f_hash . "',
			'" . $s_id . "',
			'1',
			'" . $_FILES['Filedata']['name'] . "',
			'" . mime_content_type($_WS['PATH_FILES'] . $f_hash) ."',
			'" . str_replace('.', NULL, strtolower(strrchr($_FILES['Filedata']['name'], '.'))) . "',
			'" . $_FILES['Filedata']['size'] ."',
			'" . time() . "'
		)");

		_ws_log('File ' . $f_hash . ' uploaded to ' . $s_id . '');

	else :

		exit('error uploading file!');
	endif;
endif;

?>