<?php

session_start();

if (!$_SESSION['user'])
	exit('you are not authorised to delete this file!!');

$get = array_keys($_GET);
$file = $_SERVER['DOCUMENT_ROOT'] . '/sharepoints/' . $_SESSION['folder'] . '/' . $get[0] . '.' . $get[1];

// exit($file);

if (is_file($file)){

	if (!unlink($file)){

		exit('error');
	}
	else {
		header('Location: /?' . $_SESSION['folder'] );
		exit('done');
	}
}
else {
	exit ('<b>' . $file . '</b> is no file!');
}


?>