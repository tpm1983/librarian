
<?php

require_once 'include/config.php';
require_once 'include/functions.php';

mysql_select_db('webshare', $_WS['SQL_CONNECT'][0]) or die('Could not select database.');
$query = mysql_query("SELECT * FROM files WHERE f_hash = '" . $_GET['hash'] . "'");

if (mysql_num_rows($query) < 1) :

	exit('NO FILE FOUND!');

else :

	$file = mysql_fetch_assoc($query);

	if(!file_exists($_WS['PATH_FILES'] . $file['f_hash'])) :

		exit('REFERENCE FOUND BUT MISSING FILE IN FILESYSTEM');

	endif;

endif;

$query = "UPDATE files SET time_last_download = " . time() . ", downloads = downloads + 1 WHERE f_hash = '" . $_GET['hash'] . "'";
mysql_query($query);

_ws_log('File download ' . $_GET['hash']);

if(mysql_error())
	exit(mysql_error());

header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Description: File Transfer"); 
header("Content-Length: " . $file['size']);
ob_end_clean();
readfile($_WS['PATH_FILES'] . $file['f_hash']);

?>